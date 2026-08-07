<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Mail\ContactReplyMail;
use App\Http\Requests\ReplyContactMessageRequest;
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
        $sort = $request->string('sort')->toString();

        $allowedSorts = [
            'oldest',
            'unread_first',
            'replied_first',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = '';
        }

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

            ->when(
                $sort === 'unread_first',
                function ($query) {
                    $query->orderByRaw("
                        CASE
                            WHEN status = 'unread' THEN 0
                            WHEN status = 'read' THEN 1
                            WHEN status = 'replied' THEN 2
                            ELSE 3
                        END
                    ");
                }
            )
            ->when(
                $sort === 'replied_first',
                function ($query) {
                    $query->orderByRaw("
                        CASE
                            WHEN status = 'replied' THEN 0
                            WHEN status = 'read' THEN 1
                            WHEN status = 'unread' THEN 2
                            ELSE 3
                        END
                    ");
                }
            );

        if ($sort === 'oldest') {
            $messages
                ->orderBy('created_at')
                ->orderBy('id');
        } else {
            $messages
                ->orderByDesc('created_at')
                ->orderByDesc('id');
        }

        $messages = $messages
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
                    ->orderBy('sent_at')
                    ->orderBy('id');
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
        ReplyContactMessageRequest $request,
        ContactMessage $contactMessage
    ): RedirectResponse {
        $validated = $request->validated();

        $admin = $request->user();
        $sentAt = now();

        try {
            /*
             * Chỉ lưu phản hồi sau khi gửi email thành công.
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
                    'read_at' => $contactMessage->read_at ?? $sentAt,
                    'replied_at' => $sentAt,
                ]);
            });
        } catch (Throwable $exception) {
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
                'Đã gửi phản hồi đến ' .
                $contactMessage->email .
                ' thành công.'
            );
    }

}