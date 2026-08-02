<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyContactMessageRequest;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $contacts = ContactMessage::query()
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('subject', 'like', '%' . $search . '%')
                        ->orWhere('message', 'like', '%' . $search . '%');
                });
            })
            ->when(
                in_array($status, ['unread', 'read'], true),
                function (Builder $query) use ($status) {
                    $query->where('status', $status);
                }
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $statistics = [
            'total' => ContactMessage::count(),

            'unread' => ContactMessage::where(
                'status',
                'unread'
            )->count(),

            'read' => ContactMessage::where(
                'status',
                'read'
            )->count(),
        ];

        return view(
            'admin.contacts.index',
            compact('contacts', 'statistics')
        );
    }

    /**
     * Hiển thị chi tiết thư liên hệ.
     */
    public function show(ContactMessage $contact): View
    {
        if ($contact->status === 'unread') {
            $contact->update([
                'status' => 'read',
            ]);

            $contact->refresh();
        }

        return view(
            'admin.contacts.show',
            compact('contact')
        );
    }

    /**
     * Gửi email phản hồi cho người dùng.
     */
    public function reply(
        ReplyContactMessageRequest $request,
        ContactMessage $contact
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            Mail::to($contact->email)->send(
                new ContactReplyMail(
                    contact: $contact,
                    replySubject: $validated['reply_subject'],
                    replyMessage: $validated['reply_message']
                )
            );

            $contact->update([
                'status' => 'read',
                'reply_subject' => $validated['reply_subject'],
                'reply_message' => $validated['reply_message'],
                'replied_at' => now(),
            ]);

            return redirect()
                ->route('admin.contacts.show', $contact)
                ->with(
                    'success',
                    'Đã gửi phản hồi thành công đến email ' .
                    $contact->email .
                    '.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Không thể gửi email. Vui lòng kiểm tra cấu hình SMTP và thử lại.'
                );
        }
    }

    /**
     * Cập nhật trạng thái thư.
     */
    public function updateStatus(
        Request $request,
        ContactMessage $contact
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'status' => [
                    'required',
                    'in:unread,read',
                ],
            ],
            [
                'status.required' => 'Vui lòng chọn trạng thái thư.',
                'status.in' => 'Trạng thái thư không hợp lệ.',
            ]
        );

        $contact->update([
            'status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'read'
            ? 'Đã đánh dấu thư là đã đọc.'
            : 'Đã đánh dấu thư là chưa đọc.';

        return back()->with('success', $message);
    }

    /**
     * Xóa thư liên hệ.
     */
    public function destroy(
        ContactMessage $contact
    ): RedirectResponse {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Đã xóa thư liên hệ thành công.');
    }
}