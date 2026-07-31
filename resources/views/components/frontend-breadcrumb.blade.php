@props([
    'items' => [],
])

<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">

        <nav
            class="flex flex-wrap items-center gap-2 text-sm"
            aria-label="Breadcrumb"
        >
            @foreach ($items as $item)

                @if (!$loop->last)

                    @if (!empty($item['url']))
                        <a
                            href="{{ $item['url'] }}"
                            class="font-medium text-slate-500 transition hover:text-blue-600"
                        >
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="font-medium text-slate-500">
                            {{ $item['label'] }}
                        </span>
                    @endif

                    <svg
                        class="h-4 w-4 shrink-0 text-slate-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m9 18 6-6-6-6"
                        />
                    </svg>

                @else

                    <span
                        class="min-w-0 truncate font-semibold text-slate-800"
                        aria-current="page"
                    >
                        {{ $item['label'] }}
                    </span>

                @endif

            @endforeach
        </nav>

    </div>
</section>