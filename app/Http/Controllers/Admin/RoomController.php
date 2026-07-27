<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rooms = Room::query()
            ->with('homestay')
            ->when($search, function ($query, $search) {
                $query->where(function ($roomQuery) use ($search) {
                    $roomQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('room_code', 'like', "%{$search}%")
                        ->orWhere('room_type', 'like', "%{$search}%")
                        ->orWhereHas('homestay', function ($homestayQuery) use ($search) {
                            $homestayQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $homestays = Homestay::query()
            ->orderBy('name')
            ->get();

        return view('admin.rooms.create', compact('homestays'));
    }

    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('rooms', 'public');
        }

        Room::create($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Thêm phòng thành công.');
    }

    public function show(Room $room)
    {
        $room->load('homestay');

        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $homestays = Homestay::query()
            ->orderBy('name')
            ->get();

        return view('admin.rooms.edit', compact('room', 'homestays'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();

        if ($request->boolean('remove_image')) {
            if (
                $room->image &&
                Storage::disk('public')->exists($room->image)
            ) {
                Storage::disk('public')->delete($room->image);
            }

            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if (
                $room->image &&
                Storage::disk('public')->exists($room->image)
            ) {
                Storage::disk('public')->delete($room->image);
            }

            $data['image'] = $request->file('image')
                ->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Cập nhật phòng thành công.');
    }

    public function destroy(Room $room)
    {
        if (
            $room->image &&
            Storage::disk('public')->exists($room->image)
        ) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Xóa phòng thành công.');
    }
}