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

        <p class="mb-4 text-sm font-semibold md:text-lg text-slate-500">
            Cập nhật thông tin tiện ích
            <span class="font-semibold text-slate-700">
                {{ $amenity->name }}
            </span>.
        </p>

        {{-- Form chỉnh sửa tiện ích --}}
        <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Card thông tin --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                {{-- Nội dung --}}
                <div class="space-y-7 p-6 sm:p-8">

                    {{-- Tên tiện ích --}}
                    <div>

                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                            Tên tiện ích

                            <span class="text-red-500">
                                *
                            </span>
                        </label>

                        <input  id="name" type="text" name="name" value="{{ old('name', $amenity->name) }} class=h-12"
                            maxlength="255" placeholder="Ví dụ: Ghế tình yêu" autocomplete="off" autofocus
                            class="h-12 w-full rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                            {{ $errors->has('name')
                                ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Icon --}}
                    <div>

                        <label for="icon" class="mb-2 block text-sm font-semibold text-slate-700">
                            Icon
                        </label>

                        <div class="grid gap-4 sm:grid-cols-[1fr_auto]">

                            <input id="icon" type="text" name="icon" value="{{ old('icon', $amenity->icon) }}"
                                maxlength="255" placeholder="Ví dụ: 🪑" autocomplete="off"
                                class="h-12 w-full rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('icon')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            {{-- Xem trước icon --}}
                            <div id="icon-preview"
                                class="flex min-h-12 min-w-20 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 text-2xl text-blue-600"
                                aria-label="Xem trước icon">
                                @if (!empty($currentIcon))
                                    <span>{{ $currentIcon }}</span>
                                @else
                                    {!! $defaultAmenityIcon !!}
                                @endif
                            </div>

                        </div>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Bạn có thể nhập một emoji phù hợp với tiện ích.
                            Nếu để trống, hệ thống sẽ hiển thị icon mặc định.
                        </p>

                        @error('icon')
                            <p class="mt-2 text-sm font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Mô tả --}}
                    <div>

                        <div class="mb-2 flex items-center justify-between gap-4">

                            <label for="description" class="block text-sm font-semibold text-slate-700">
                                Mô tả
                            </label>

                            <span id="description-counter" class="text-xs font-medium text-slate-400">
                                0 / 1000 ký tự
                            </span>

                        </div>

                        <textarea id="description" name="description" rows="5" maxlength="1000"
                            placeholder="Nhập mô tả ngắn về tiện ích..."
                            class="w-full resize-y rounded-2xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                            {{ $errors->has('description')
                                ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">{{ old('description', $amenity->description) }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Trạng thái --}}
                    <fieldset>

                        <legend class="mb-3 text-sm font-semibold text-slate-700">
                            Trạng thái
                            <span class="text-red-500">*</span>
                        </legend>

                        <div class="grid gap-4 sm:grid-cols-2">

                            {{-- Hoạt động --}}
                            <label for="status-active" class="relative block cursor-pointer">
                                <input id="status-active" type="radio" name="status" value="1"
                                    class="peer absolute left-5 top-7 h-5 w-5 cursor-pointer border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    {{ $currentStatus === '1' ? 'checked' : '' }}>

                                <div
                                    class="min-h-[92px] rounded-2xl border border-slate-300 bg-white py-5 pl-14 pr-5 transition
                    hover:border-emerald-400 hover:bg-emerald-50/50
                    peer-checked:border-emerald-500
                    peer-checked:bg-emerald-50
                    peer-checked:ring-2 peer-checked:ring-emerald-200">
                                    <p class="font-semibold text-slate-900">
                                        Hoạt động
                                    </p>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        Tiện ích được phép hiển thị và sử dụng.
                                    </p>
                                </div>
                            </label>

                            {{-- Tạm khóa --}}
                            <label for="status-inactive" class="relative block cursor-pointer">
                                <input id="status-inactive" type="radio" name="status" value="0"
                                    class="peer absolute left-5 top-7 h-5 w-5 cursor-pointer border-slate-300 text-red-600 focus:ring-red-500"
                                    {{ $currentStatus === '0' ? 'checked' : '' }}>

                                <div
                                    class="min-h-[92px] rounded-2xl border border-slate-300 bg-white py-5 pl-14 pr-5 transition
                    hover:border-red-400 hover:bg-red-50/50
                    peer-checked:border-red-500
                    peer-checked:bg-red-50
                    peer-checked:ring-2 peer-checked:ring-red-200">
                                    <p class="font-semibold text-slate-900">
                                        Tạm khóa
                                    </p>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        Tiện ích chưa được phép hiển thị hoặc sử dụng.
                                    </p>
                                </div>
                            </label>

                        </div>

                        @error('status')
                            <p class="mt-2 text-sm font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </fieldset>

                </div>

            </section>

            {{-- Nút hành động --}}
            <div
                class="flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-end">

                <a href="{{ route('admin.amenities.index') }}"
                    class="inline-flex cursor-pointer items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200">
                    Hủy
                </a>

                <button type="submit"
                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:scale-[0.99]">
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
                        'text-slate-400'
                    );

                    descriptionCounter.classList.add(
                        'text-red-500'
                    );

                    return;
                }

                descriptionCounter.classList.remove(
                    'text-red-500'
                );

                descriptionCounter.classList.add(
                    'text-slate-400'
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
