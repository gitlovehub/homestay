<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm tiện ích | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-8">

            <a
                href="{{ route('admin.amenities.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                <span aria-hidden="true">←</span>
                Quay lại danh sách tiện ích
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Thêm tiện ích
            </h1>

            <p class="mt-2 text-slate-500">
                Nhập thông tin để tạo một tiện ích mới cho Homestay.
            </p>

        </div>

        {{-- Form thêm tiện ích --}}
        <form
            action="{{ route('admin.amenities.store') }}"
            method="POST"
            class="space-y-6"
        >
            @csrf

            {{-- Card thông tin chính --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                {{-- Phần đầu card --}}
                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

                    <h2 class="text-xl font-bold text-slate-900">
                        Thông tin tiện ích
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Các trường có dấu
                        <span class="font-semibold text-red-500">*</span>
                        là bắt buộc.
                    </p>

                </div>

                {{-- Nội dung form --}}
                <div class="space-y-7 p-6 sm:p-8">

                    {{-- Tên tiện ích --}}
                    <div>

                        <label
                            for="name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tên tiện ích
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Ví dụ: Wi-Fi miễn phí"
                            autocomplete="off"
                            class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('name')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                }}"
                        >

                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Icon --}}
                    <div>

                        <label
                            for="icon"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Icon
                        </label>

                        <div class="grid gap-4 sm:grid-cols-[1fr_auto]">

                            <input
                                id="icon"
                                type="text"
                                name="icon"
                                value="{{ old('icon') }}"
                                placeholder="Ví dụ: 📶"
                                autocomplete="off"
                                class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('icon')
                                        ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                    }}"
                            >

                            <div
                                class="flex min-h-12 min-w-20 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4"
                            >
                                <span
                                    id="icon-preview"
                                    class="text-2xl"
                                    aria-label="Xem trước icon"
                                >
                                    {{ old('icon', '⚡') }}
                                </span>
                            </div>

                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            Có thể nhập emoji hoặc tên icon đang được sử dụng trong hệ thống.
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
                                0 ký tự
                            </span>

                        </div>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Nhập mô tả ngắn về tiện ích..."
                            class="w-full resize-y rounded-2xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('description')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                }}"
                        >{{ old('description') }}</textarea>

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

                            {{-- Đang hoạt động --}}
                            <label
                                for="status-active"
                                class="group cursor-pointer"
                            >
                                <input
                                    id="status-active"
                                    type="radio"
                                    name="status"
                                    value="1"
                                    class="peer sr-only"
                                    {{ old('status', '1') == '1' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-2xl border border-slate-300 bg-white p-5 transition
                                        group-hover:border-emerald-300 group-hover:bg-emerald-50/50
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                        peer-focus-visible:ring-4 peer-focus-visible:ring-emerald-100"
                                >
                                    <div class="flex items-start gap-4">

                                        <div
                                            class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                                        >
                                            ✓
                                        </div>

                                        <div>

                                            <p class="font-semibold text-slate-900">
                                                Đang hoạt động
                                            </p>

                                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                                Tiện ích được sử dụng và hiển thị trong hệ thống.
                                            </p>

                                        </div>

                                    </div>
                                </div>
                            </label>

                            {{-- Ngừng hoạt động --}}
                            <label
                                for="status-inactive"
                                class="group cursor-pointer"
                            >
                                <input
                                    id="status-inactive"
                                    type="radio"
                                    name="status"
                                    value="0"
                                    class="peer sr-only"
                                    {{ old('status') == '0' ? 'checked' : '' }}
                                >

                                <div
                                    class="rounded-2xl border border-slate-300 bg-white p-5 transition
                                        group-hover:border-slate-400 group-hover:bg-slate-50
                                        peer-checked:border-slate-500 peer-checked:bg-slate-100
                                        peer-focus-visible:ring-4 peer-focus-visible:ring-slate-200"
                                >
                                    <div class="flex items-start gap-4">

                                        <div
                                            class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-600"
                                        >
                                            —
                                        </div>

                                        <div>

                                            <p class="font-semibold text-slate-900">
                                                Ngừng hoạt động
                                            </p>

                                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                                Tiện ích vẫn được lưu nhưng tạm thời không sử dụng.
                                            </p>

                                        </div>

                                    </div>
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

            </div>

            {{-- Nút hành động --}}
            <div
                class="flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-end"
            >

                <a
                    href="{{ route('admin.amenities.index') }}"
                    class="inline-flex cursor-pointer items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:scale-[0.99]"
                >
                    Thêm tiện ích
                </button>

            </div>

        </form>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iconInput = document.getElementById('icon');
            const iconPreview = document.getElementById('icon-preview');

            const descriptionInput = document.getElementById('description');
            const descriptionCounter = document.getElementById('description-counter');

            function updateIconPreview() {
                const iconValue = iconInput.value.trim();

                iconPreview.textContent = iconValue || '⚡';
            }

            function updateDescriptionCounter() {
                const characterCount = descriptionInput.value.length;

                descriptionCounter.textContent = `${characterCount} ký tự`;
            }

            iconInput.addEventListener('input', updateIconPreview);
            descriptionInput.addEventListener('input', updateDescriptionCounter);

            updateIconPreview();
            updateDescriptionCounter();
        });
    </script>

</body>

</html>