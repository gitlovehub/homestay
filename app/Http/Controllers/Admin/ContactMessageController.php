<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactMessageController extends Controller
{
    /**
     * Hiển thị danh sách thư liên hệ.
     */
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $status = $request->string('status')->toString();

        $messages = ContactMessage::query()
            ->with([
                'user:id,name,email',
            ])

            // Tìm kiếm
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('subject', 'like', '%' . $search . '%')
                        ->orWhere('message', 'like', '%' . $search . '%');
                });
            })

            // Lọc trạng thái
            ->when(
                in_array($status, ['unread', 'read', 'replied'], true),
                function ($query) use ($status) {
                    $query->where('status', $status);
                }
            )

            // Thư chưa đọc đưa lên đầu
            ->orderByRaw("
                CASE
                    WHEN status = 'unread' THEN 0
                    WHEN status = 'read' THEN 1
                    WHEN status = 'replied' THEN 2
                    ELSE 3
                END
            ")

            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $statistics = [
            'total' => ContactMessage::count(),

            'unread' => ContactMessage::query()
                ->where('status', 'unread')
                ->count(),

            'read' => ContactMessage::query()
                ->where('status', 'read')
                ->count(),

            'replied' => ContactMessage::query()
                ->where('status', 'replied')
                ->count(),
        ];

        return view(
            'admin.contact-messages.index',
            compact('messages', 'statistics')
        );
    }

    /**
     * Hiển thị chi tiết một thư liên hệ.
     */
    public function show(ContactMessage $contactMessage): View
    {
        /*
         * Khi Admin mở thư chưa đọc:
         * - chuyển trạng thái sang read
         * - lưu thời gian đọc
         */
        if ($contactMessage->status === 'unread') {
            $contactMessage->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        /*
         * Nạp thông tin người gửi và lịch sử phản hồi.
         */
        $contactMessage->load([
            'user:id,name,email,phone,status',
            'replies' => function ($query) {
                $query
                    ->with('admin:id,name,email')
                    ->oldest('sent_at');
            },
        ]);

        return view(
            'admin.contact-messages.show',
            compact('contactMessage')
        );
    }

    /**
     * Gửi phản hồi đến email người dùng.
     */
    public function reply(
        Request $request,
        ContactMessage $contactMessage
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'reply_subject' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'reply_message' => [
                    'required',
                    'string',
                    'min:10',
                    'max:5000',
                ],
            ],
            [
                'reply_subject.required' => 'Vui lòng nhập tiêu đề phản hồi.',
                'reply_subject.max' => 'Tiêu đề phản hồi không được vượt quá 255 ký tự.',

                'reply_message.required' => 'Vui lòng nhập nội dung phản hồi.',
                'reply_message.min' => 'Nội dung phản hồi phải có ít nhất 10 ký tự.',
                'reply_message.max' => 'Nội dung phản hồi không được vượt quá 5000 ký tự.',
            ]
        );

        $admin = $request->user();
        $sentAt = now();

        try {
            /*
             * Chỉ lưu phản hồi sau khi Laravel gửi email thành công.
             */
            Mail::to($contactMessage->email)->send(
                new ContactReplyMail(
                    contactMessage: $contactMessage,
                    replySubject: $validated['reply_subject'],
                    replyMessage: $validated['reply_message']
                )
            );

            DB::transaction(function () use ($contactMessage, $admin, $validated, $sentAt) {
                /*
                 * Lưu lịch sử phản hồi.
                 */
                $contactMessage->replies()->create([
                    'admin_id' => $admin->id,
                    'subject' => $validated['reply_subject'],
                    'message' => $validated['reply_message'],
                    'sent_at' => $sentAt,
                ]);

                /*
                 * Cập nhật trạng thái thư.
                 */
                $contactMessage->update([
                    'status' => 'replied',

                    /*
                     * Trường hợp admin phản hồi trực tiếp mà thư chưa có read_at.
                     */
                    'read_at' => $contactMessage->read_at ?? $sentAt,

                    'replied_at' => $sentAt,
                ]);
            });
        } catch (Throwable $exception) {
            /*
             * Ghi lỗi vào log để kiểm tra nhưng không hiển thị
             * thông tin kỹ thuật cho người dùng.
             */
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Không thể gửi phản hồi lúc này. Vui lòng kiểm tra cấu hình email và thử lại.'
                );
        }

        return redirect()
            ->route(
                'admin.contact-messages.show',
                $contactMessage
            )
            ->with(
                'success',
                'Đã gửi phản hồi đến ' . $contactMessage->email . ' thành công.'
            );
    }
}