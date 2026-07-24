<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm mới Homestay | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Tiêu đề --}}
        <div class="mb-8">

            <a
                href="{{ route('admin.homestays.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                <span aria-hidden="true">←</span>
                Quay lại danh sách Homestay
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Thêm mới Homestay
            </h1>

            <p class="mt-2 text-slate-500">
                Nhập đầy đủ thông tin để thêm Homestay mới vào hệ thống.
            </p>

        </div>

        <form
            action="{{ route('admin.homestays.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        >
            @csrf

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Thông tin cơ bản --}}
                <section>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin cơ bản
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Tên, danh mục và chủ sở hữu của Homestay.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Tên Homestay --}}
                        <div class="md:col-span-2">

                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Tên Homestay
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                autofocus
                                placeholder="Ví dụ: Ocean View Homestay"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('name')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Slug --}}
                        <div class="md:col-span-2">

                            <label
                                for="slug"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Slug
                            </label>

                            <input
                                id="slug"
                                name="slug"
                                type="text"
                                value="{{ old('slug') }}"
                                placeholder="Để trống để hệ thống tự tạo từ tên Homestay"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('slug')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                Ví dụ: “Ocean View Homestay” sẽ có slug là “ocean-view-homestay”.
                            </p>

                            @error('slug')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Danh mục --}}
                        <div>

                            <label
                                for="category_id"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Danh mục
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition
                                    {{ $errors->has('category_id')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >
                                <option value="">-- Chọn danh mục --</option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id') == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Chủ sở hữu --}}
                        <div>

                            <label
                                for="owner_id"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Chủ sở hữu
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="owner_id"
                                name="owner_id"
                                class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition
                                    {{ $errors->has('owner_id')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >
                                <option value="">-- Chọn chủ sở hữu --</option>

                                @foreach ($owners as $owner)
                                    <option
                                        value="{{ $owner->id }}"
                                        @selected(old('owner_id') == $owner->id)
                                    >
                                        {{ $owner->name }} — {{ $owner->email }}
                                    </option>
                                @endforeach
                            </select>

                            @error('owner_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Giá và thời gian --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Giá và thời gian
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thiết lập giá cơ bản và khung giờ nhận, trả phòng.
                        </p>

                    </div>

                    <div class="grid gap-6 md:grid-cols-3">

                        {{-- Giá cơ bản --}}
                        <div>

                            <label
                                for="base_price"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Giá cơ bản
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <input
                                    id="base_price"
                                    name="base_price"
                                    type="number"
                                    min="0"
                                    step="1000"
                                    value="{{ old('base_price') }}"
                                    placeholder="Ví dụ: 1500000"
                                    class="w-full rounded-xl border px-4 py-3 pr-16 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                        {{ $errors->has('base_price')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                        }}"
                                >

                                <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    VNĐ
                                </span>

                            </div>

                            @error('base_price')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Giờ nhận phòng --}}
                        <div>

                            <label
                                for="check_in_time"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Giờ nhận phòng
                            </label>

                            <input
                                id="check_in_time"
                                name="check_in_time"
                                type="time"
                                value="{{ old('check_in_time', '14:00') }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition
                                    {{ $errors->has('check_in_time')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('check_in_time')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Giờ trả phòng --}}
                        <div>

                            <label
                                for="check_out_time"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Giờ trả phòng
                            </label>

                            <input
                                id="check_out_time"
                                name="check_out_time"
                                type="time"
                                value="{{ old('check_out_time', '12:00') }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition
                                    {{ $errors->has('check_out_time')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('check_out_time')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Địa chỉ và liên hệ --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Địa chỉ và liên hệ
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thông tin vị trí và số điện thoại liên hệ.
                        </p>

                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Địa chỉ --}}
                        <div class="md:col-span-2">

                            <label
                                for="address"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Địa chỉ
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="address"
                                name="address"
                                type="text"
                                value="{{ old('address') }}"
                                placeholder="Ví dụ: 123 đường Trần Phú"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('address')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('address')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Thành phố --}}
                        <div>

                            <label
                                for="city"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Thành phố
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="city"
                                name="city"
                                type="text"
                                value="{{ old('city') }}"
                                placeholder="Ví dụ: Đà Lạt"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('city')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('city')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Số điện thoại --}}
                        <div>

                            <label
                                for="phone"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Số điện thoại
                            </label>

                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                inputmode="numeric"
                                maxlength="11"
                                value="{{ old('phone') }}"
                                placeholder="Ví dụ: 0987654321"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('phone')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('phone')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Vĩ độ --}}
                        <div>

                            <label
                                for="latitude"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Vĩ độ
                            </label>

                            <input
                                id="latitude"
                                name="latitude"
                                type="number"
                                step="0.0000001"
                                value="{{ old('latitude') }}"
                                placeholder="Ví dụ: 11.940419"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('latitude')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('latitude')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Kinh độ --}}
                        <div>

                            <label
                                for="longitude"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Kinh độ
                            </label>

                            <input
                                id="longitude"
                                name="longitude"
                                type="number"
                                step="0.0000001"
                                value="{{ old('longitude') }}"
                                placeholder="Ví dụ: 108.458313"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('longitude')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            @error('longitude')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Tiện ích --}}
                <section>

                    <div class="mb-5">

                        <h2 class="text-lg font-bold text-slate-900">
                            Tiện ích
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn những tiện ích đang có tại Homestay.
                        </p>

                    </div>

                    @if ($amenities->isNotEmpty())

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach ($amenities as $amenity)

                                <label
                                    for="amenity-{{ $amenity->id }}"
                                    class="group cursor-pointer"
                                >

                                    <input
                                        id="amenity-{{ $amenity->id }}"
                                        name="amenities[]"
                                        type="checkbox"
                                        value="{{ $amenity->id }}"
                                        class="peer sr-only"
                                        @checked(in_array($amenity->id, old('amenities', [])))
                                    >

                                    <div
                                        class="flex h-full items-start gap-3 rounded-2xl border border-slate-300 bg-white p-4 transition
                                            group-hover:border-blue-300
                                            group-hover:bg-blue-50/50
                                            peer-checked:border-blue-500
                                            peer-checked:bg-blue-50
                                            peer-focus-visible:ring-4
                                            peer-focus-visible:ring-blue-100"
                                    >

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xl">
                                            {{ $amenity->icon ?: '✓' }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-semibold text-slate-900">
                                                {{ $amenity->name }}
                                            </p>

                                            @if ($amenity->description)
                                                <p class="mt-1 line-clamp-2 text-sm leading-5 text-slate-500">
                                                    {{ $amenity->description }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                </label>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                            <p class="text-sm font-medium text-slate-600">
                                Chưa có tiện ích đang hoạt động.
                            </p>

                        </div>

                    @endif

                    @error('amenities')
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('amenities.*')
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Hình ảnh --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Ảnh đại diện
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn ảnh chính được sử dụng để đại diện cho Homestay.
                        </p>

                    </div>

                    <div class="grid items-start gap-6 md:grid-cols-2">

                        {{-- Chọn ảnh --}}
                        <div>
                            <div class="mb-2 flex min-h-6 items-center">
                                <p class="text-sm font-semibold text-slate-700">
                                    Chọn ảnh
                                </p>
                            </div>

                            <label
                                for="thumbnail"
                                class="flex h-64 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50"
                            >
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl">
                                    📷
                                </div>

                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    Nhấn để chọn ảnh đại diện
                                </p>

                                <p
                                    id="thumbnail-name"
                                    class="mt-2 max-w-full truncate text-xs text-slate-400"
                                >
                                    JPG, JPEG, PNG hoặc WEBP. Tối đa 3MB.
                                </p>
                            </label>

                            <input
                                id="thumbnail"
                                name="thumbnail"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="hidden"
                            >

                            @error('thumbnail')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Xem trước ảnh --}}
                        <div id="thumbnail-preview-wrapper" class="hidden">

                            <div class="mb-2 flex min-h-6 items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-slate-700">
                                    Xem trước ảnh
                                </p>

                                <button
                                    id="remove-thumbnail"
                                    type="button"
                                    class="cursor-pointer text-sm font-semibold text-red-600 transition hover:text-red-700"
                                >
                                    Xóa ảnh
                                </button>
                            </div>

                            <img
                                id="thumbnail-preview"
                                src=""
                                alt="Ảnh đại diện Homestay"
                                class="h-64 w-full rounded-2xl border border-slate-200 object-cover"
                            >
                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Mô tả và chính sách --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Nội dung Homestay
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thêm phần giới thiệu và các chính sách lưu trú.
                        </p>

                    </div>

                    <div class="grid gap-6">

                        {{-- Mô tả --}}
                        <div>

                            <div class="mb-2 flex items-center justify-between gap-4">

                                <label
                                    for="description"
                                    class="block text-sm font-semibold text-slate-700"
                                >
                                    Mô tả
                                </label>

                                <span
                                    id="description-counter"
                                    class="text-xs font-medium text-slate-400"
                                >
                                    0/3000 ký tự
                                </span>

                            </div>

                            <textarea
                                id="description"
                                name="description"
                                rows="7"
                                maxlength="3000"
                                placeholder="Nhập nội dung giới thiệu về Homestay..."
                                class="w-full resize-y rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('description')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Chính sách --}}
                        <div>

                            <div class="mb-2 flex items-center justify-between gap-4">

                                <label
                                    for="policy"
                                    class="block text-sm font-semibold text-slate-700"
                                >
                                    Chính sách
                                </label>

                                <span
                                    id="policy-counter"
                                    class="text-xs font-medium text-slate-400"
                                >
                                    0/3000 ký tự
                                </span>

                            </div>

                            <textarea
                                id="policy"
                                name="policy"
                                rows="6"
                                maxlength="3000"
                                placeholder="Ví dụ: Không hút thuốc, không mang vật nuôi, giữ yên lặng sau 22 giờ..."
                                class="w-full resize-y rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('policy')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >{{ old('policy') }}</textarea>

                            @error('policy')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Trạng thái --}}
                <section>

                    <div class="mb-4">

                        <h2 class="text-lg font-bold text-slate-900">
                            Trạng thái
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn trạng thái hoạt động của Homestay.
                        </p>

                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">

                        {{-- Hoạt động --}}
                        <label
                            for="status-active"
                            class="cursor-pointer rounded-2xl border border-slate-300 bg-white p-4 transition
                                hover:border-emerald-400 hover:bg-emerald-50
                                has-[:checked]:border-emerald-500
                                has-[:checked]:bg-emerald-50"
                        >

                            <div class="flex items-start gap-3">

                                <input
                                    id="status-active"
                                    name="status"
                                    type="radio"
                                    value="1"
                                    @checked(old('status', '1') == '1')
                                    class="mt-1 h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                >

                                <div>

                                    <p class="font-semibold text-slate-900">
                                        Hoạt động
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Homestay được phép hiển thị và sử dụng.
                                    </p>

                                </div>

                            </div>

                        </label>

                        {{-- Tạm khóa --}}
                        <label
                            for="status-inactive"
                            class="cursor-pointer rounded-2xl border border-slate-300 bg-white p-4 transition
                                hover:border-red-400 hover:bg-red-50
                                has-[:checked]:border-red-500
                                has-[:checked]:bg-red-50"
                        >

                            <div class="flex items-start gap-3">

                                <input
                                    id="status-inactive"
                                    name="status"
                                    type="radio"
                                    value="0"
                                    @checked(old('status') == '0')
                                    class="mt-1 h-4 w-4 border-slate-300 text-red-600 focus:ring-red-500"
                                >

                                <div>

                                    <p class="font-semibold text-slate-900">
                                        Tạm khóa
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Homestay tạm thời không được hiển thị.
                                    </p>

                                </div>

                            </div>

                        </label>

                    </div>

                    @error('status')
                        <p class="mt-2 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </section>

            </div>

            {{-- Nút hành động --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

                <a
                    href="{{ route('admin.homestays.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                >
                    Thêm mới Homestay
                </button>

            </div>

        </form>

    </main>

    <script>
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailName = document.getElementById('thumbnail-name');
        const previewWrapper = document.getElementById('thumbnail-preview-wrapper');
        const previewImage = document.getElementById('thumbnail-preview');
        const removeThumbnailButton = document.getElementById('remove-thumbnail');

        let previewUrl = null;

        thumbnailInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                hideThumbnailPreview();
                return;
            }

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            previewUrl = URL.createObjectURL(file);
            previewImage.src = previewUrl;
            thumbnailName.textContent = file.name;
            previewWrapper.classList.remove('hidden');
        });

        removeThumbnailButton.addEventListener('click', function () {
            thumbnailInput.value = '';
            hideThumbnailPreview();
        });

        function hideThumbnailPreview() {
            previewWrapper.classList.add('hidden');
            thumbnailName.textContent = 'JPG, JPEG, PNG hoặc WEBP. Tối đa 3MB.';

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            previewImage.src = '';
        }

        const description = document.getElementById('description');
        const descriptionCounter = document.getElementById('description-counter');

        const policy = document.getElementById('policy');
        const policyCounter = document.getElementById('policy-counter');

        const updateCounter = (input, counter, maximum) => {
            counter.textContent = `${input.value.length}/${maximum} ký tự`;
        };

        description.addEventListener('input', function () {
            updateCounter(description, descriptionCounter, 3000);
        });

        policy.addEventListener('input', function () {
            updateCounter(policy, policyCounter, 3000);
        });

        updateCounter(description, descriptionCounter, 3000);
        updateCounter(policy, policyCounter, 3000);
    </script>

</body>

</html>