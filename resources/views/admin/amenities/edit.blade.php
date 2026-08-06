@extends('layouts.admin')

@section('title', 'Chỉnh sửa tiện ích | HomeStayGo')

@section('page-title', 'Chỉnh sửa tiện ích')

@section('content')
    <div class="mx-auto max-w-4xl">

        @php
            $defaultAmenityIcon = '
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.8"
                stroke="currentColor"
                class="h-6 w-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.847-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 0 0 2.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"
                />
            </svg>
        ';

            $currentIcon = old('icon', $amenity->icon);

            $currentStatus = (string) old('status', $amenity->status ? '1' : '0');
        @endphp

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Cập nhật thông tin tiện ích
                <span class="font-bold text-blue-600 dark:text-blue-400">
                    {{ $amenity->name }}
                </span>.
            </h2>

            <a href="{{ route('admin.amenities.index') }}"
                class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                ←
                Trở về danh sách tiện ích
            </a>
        </div>

        {{-- Form chỉnh sửa tiện ích --}}
        <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Card thông tin --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="space-y-7 p-6 sm:p-8">

                    {{-- Tên tiện ích --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Tên tiện ích
                            <span class="text-red-500">*</span>
                        </label>

                        <input id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $amenity->name) }}"
                            maxlength="255"
                            placeholder="Ví dụ: Ghế tình yêu"
                            autocomplete="off"
                            autofocus
                            class="h-11 w-full rounded-xl border px-4 text-slate-900 outline-none transition placeholder:text-slate-400 dark:placeholder:text-slate-500
                                {{ $errors->has('name')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:bg-red-950/30 dark:text-slate-100 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                    : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon --}}
                    <div>
                        <label for="icon" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Icon
                        </label>

                        <div class="grid gap-4 sm:grid-cols-[1fr_auto]">
                            <input id="icon"
                                type="text"
                                name="icon"
                                value="{{ old('icon', $amenity->icon) }}"
                                maxlength="255"
                                placeholder="Ví dụ: 🪑"
                                autocomplete="off"
                                class="h-11 w-full rounded-xl border px-4 text-slate-900 outline-none transition placeholder:text-slate-400 dark:placeholder:text-slate-500
                                    {{ $errors->has('icon')
                                        ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:bg-red-950/30 dark:text-slate-100 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                            {{-- Xem trước icon --}}
                            <div id="icon-preview"
                                class="flex h-11 min-w-20 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 text-2xl text-blue-600 dark:border-slate-600 dark:bg-slate-900 dark:text-blue-400"
                                aria-label="Xem trước icon">
                                @if (!empty($currentIcon))
                                    <span>{{ $currentIcon }}</span>
                                @else
                                    {!! $defaultAmenityIcon !!}
                                @endif
                            </div>
                        </div>

                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Bạn có thể nhập một emoji phù hợp với tiện ích.
                            Nếu để trống, hệ thống sẽ hiển thị icon mặc định.
                        </p>

                        @error('icon')
                            <p class="mt-2 text-sm font-medium text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mô tả --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-4">
                            <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Mô tả
                            </label>
                            <span id="description-counter" class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                0 / 1000 ký tự
                            </span>
                        </div>

                        <textarea id="description"
                                name="description"
                                rows="5"
                                maxlength="1000"
                                placeholder="Nhập mô tả ngắn về tiện ích..."
                                class="w-full resize-y rounded-2xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 dark:placeholder:text-slate-500
                                    {{ $errors->has('description')
                                        ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:bg-red-950/30 dark:text-slate-100 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">{{ old('description', $amenity->description) }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm font-medium text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <section>
                        <div class="mb-6">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                Trạng thái tiện ích
                            </h2>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Chọn trạng thái hoạt động hiện tại của tiện ích.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">

                            {{-- Hoạt động --}}
                            <label class="cursor-pointer">
                                <input type="radio"
                                    name="status"
                                    value="1"
                                    class="peer sr-only"
                                    @checked($currentStatus === '1')>

                                <div
                                    class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                        hover:border-emerald-300
                                        peer-checked:border-emerald-500
                                        peer-checked:bg-emerald-50
                                        peer-checked:[&_.radio-circle]:border-emerald-500
                                        peer-checked:[&_.radio-dot]:opacity-100
                                        dark:border-slate-700
                                        dark:bg-slate-800
                                        dark:hover:border-emerald-600
                                        dark:peer-checked:border-emerald-500
                                        dark:peer-checked:bg-emerald-950/40">

                                    <div
                                        class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                        <div
                                            class="radio-dot h-2.5 w-2.5 rounded-full bg-emerald-500 opacity-0 transition">
                                        </div>
                                    </div>

                                    <span class="font-bold text-slate-900 dark:text-slate-100">
                                        Hoạt động
                                    </span>
                                </div>
                            </label>

                            {{-- Tạm khóa --}}
                            <label class="cursor-pointer">
                                <input type="radio"
                                    name="status"
                                    value="0"
                                    class="peer sr-only"
                                    @checked($currentStatus === '0')>

                                <div
                                    class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                        hover:border-red-300
                                        peer-checked:border-red-500
                                        peer-checked:bg-red-50
                                        peer-checked:[&_.radio-circle]:border-red-500
                                        peer-checked:[&_.radio-dot]:opacity-100
                                        dark:border-slate-700
                                        dark:bg-slate-800
                                        dark:hover:border-red-600
                                        dark:peer-checked:border-red-500
                                        dark:peer-checked:bg-red-950/40">

                                    <div
                                        class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                        <div
                                            class="radio-dot h-2.5 w-2.5 rounded-full bg-red-500 opacity-0 transition">
                                        </div>
                                    </div>

                                    <span class="font-bold text-slate-900 dark:text-slate-100">
                                        Tạm khóa
                                    </span>
                                </div>
                            </label>

                        </div>

                        @error('status')
                            <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </section>

                </div>
            </section>

            {{-- Nút hành động --}}
            <div class="flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('admin.amenities.index') }}"
                    class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-700 sm:w-auto">
                    Hủy
                </a>

                <button type="submit"
                    class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40 sm:w-auto">
                    Lưu thay đổi
                </button>
            </div>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconInput = document.getElementById('icon');
            const iconPreview = document.getElementById('icon-preview');

            const descriptionInput =
                document.getElementById('description');

            const descriptionCounter =
                document.getElementById('description-counter');

            const defaultAmenityIcon = @json($defaultAmenityIcon);
            const maximumDescriptionLength = 1000;

            function updateIconPreview() {
                const iconValue = iconInput.value.trim();

                if (iconValue !== '') {
                    iconPreview.textContent = iconValue;
                    return;
                }

                iconPreview.innerHTML = defaultAmenityIcon;
            }

            function updateDescriptionCounter() {
                const characterCount =
                    descriptionInput.value.length;

                descriptionCounter.textContent =
                    `${characterCount} / ${maximumDescriptionLength} ký tự`;

                if (characterCount >= maximumDescriptionLength) {
                    descriptionCounter.classList.remove(
                        'text-slate-400',
                        'dark:text-slate-500'
                    );

                    descriptionCounter.classList.add(
                        'text-red-500',
                        'dark:text-red-400'
                    );

                    return;
                }

                descriptionCounter.classList.remove(
                    'text-red-500',
                    'dark:text-red-400'
                );

                descriptionCounter.classList.add(
                    'text-slate-400',
                    'dark:text-slate-500'
                );
            }

            iconInput.addEventListener(
                'input',
                updateIconPreview
            );

            descriptionInput.addEventListener(
                'input',
                updateDescriptionCounter
            );

            updateIconPreview();
            updateDescriptionCounter();
        });
    </script>
@endsection