<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}