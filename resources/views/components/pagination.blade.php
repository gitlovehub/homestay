@php
    $layout ??= 'col';
    $showInfo ??= true;
    $isRow = $layout === 'row';
@endphp

@if ($paginator->hasPages())
    <nav
        x-data="{ loading: false }"
        role="navigation"
        aria-label="Điều hướng phân trang"
        @class([
            'relative flex w-full gap-4',

            // Dạng cột
            'flex-col items-center' => !$isRow,

            // Dạng hàng, có thông tin kết quả
            'flex-col items-center sm:flex-row sm:justify-between' =>
                $isRow && $showInfo,

            // Dạng hàng, không có thông tin kết quả
            'flex-col items-center sm:flex-row sm:justify-end' =>
                $isRow && !$showInfo,
        ])
    >
        {{-- Thông tin kết quả --}}
        @if ($showInfo)
            <p
                @class([
                    'text-sm text-slate-500',
                    'text-center' => !$isRow,
                    'text-center sm:text-left' => $isRow,
                ])
            >
                Hiển thị

                <span class="font-semibold text-slate-800">
                    {{ $paginator->firstItem() }}
                </span>

                đến

                <span class="font-semibold text-slate-800">
                    {{ $paginator->lastItem() }}
                </span>

                trong tổng số

                <span class="font-bold text-blue-600">
                    {{ $paginator->total() }}
                </span>

                kết quả
            </p>
        @endif

        {{-- Khu vực nút phân trang và trạng thái chuyển trang --}}
        <div
            @class([
                'flex flex-col gap-2',
                'items-center' => !$isRow,
                'items-center sm:items-end' => $isRow,
            ])
        >
            {{-- Các nút phân trang --}}
            <div
                class="flex max-w-full items-center gap-1 overflow-x-auto
                       rounded-2xl border border-slate-200 bg-white
                       p-1.5 shadow-sm"
            >
                {{-- Nút trang trước --}}
                @if ($paginator->onFirstPage())
                    <span
                        aria-disabled="true"
                        aria-label="Không có trang trước"
                        class="inline-flex h-10 cursor-not-allowed items-center
                               justify-center rounded-xl px-3
                               text-sm font-semibold text-slate-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </span>
                @else
                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        rel="prev"
                        aria-label="Đi đến trang trước"
                        @click="loading = true"
                        class="inline-flex h-10 items-center justify-center
                               rounded-xl px-3 text-sm font-semibold
                               text-slate-600 transition duration-200
                               hover:bg-blue-50 hover:text-blue-600"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </a>
                @endif

                {{-- Danh sách số trang --}}
                @foreach ($elements as $element)
                    {{-- Dấu ba chấm --}}
                    @if (is_string($element))
                        <span
                            class="inline-flex h-10 min-w-[40px] items-center
                                   justify-center px-2 text-sm text-slate-400"
                        >
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Các số trang --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page === $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    aria-label="Trang hiện tại, trang {{ $page }}"
                                    class="inline-flex h-10 min-w-[40px] items-center
                                           justify-center rounded-xl bg-blue-600
                                           px-3 text-sm font-bold text-white
                                           shadow-sm"
                                >
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    aria-label="Đi đến trang {{ $page }}"
                                    @click="loading = true"
                                    class="inline-flex h-10 min-w-[40px] items-center
                                           justify-center rounded-xl px-3
                                           text-sm font-semibold text-slate-600
                                           transition duration-200
                                           hover:bg-blue-50 hover:text-blue-600"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Nút trang tiếp theo --}}
                @if ($paginator->hasMorePages())
                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        rel="next"
                        aria-label="Đi đến trang tiếp theo"
                        @click="loading = true"
                        class="inline-flex h-10 items-center justify-center
                               rounded-xl px-3 text-sm font-semibold
                               text-slate-600 transition duration-200
                               hover:bg-blue-50 hover:text-blue-600"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a>
                @else
                    <span
                        aria-disabled="true"
                        aria-label="Không có trang tiếp theo"
                        class="inline-flex h-10 cursor-not-allowed items-center
                               justify-center rounded-xl px-3
                               text-sm font-semibold text-slate-300"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </span>
                @endif
            </div>

            {{-- Hiệu ứng Alpine khi chuyển trang --}}
            <div
                x-cloak
                x-show="loading"
                x-transition.opacity
                class="flex items-center gap-2 text-sm font-medium text-blue-600"
            >
                <svg
                    class="h-4 w-4 animate-spin"
                    fill="none"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V4a4 4 0 00-4 4H4z"
                    ></path>
                </svg>

                <span>Đang chuyển trang...</span>
            </div>
        </div>
    </nav>
@endif