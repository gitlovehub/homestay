@if (session('success'))
    <div
        x-data="{ show: true }"
        x-init="
            setTimeout(() => show = false, 3000)
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed left-1/2 top-8 z-9999 -translate-x-1/2"
        style="display:none;"
    >

        <div class="flex items-center gap-3 rounded-xl bg-green-100 border border-green-400 text-green-700 px-6 py-4 shadow-2xl">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7" />

            </svg>

            <span class="font-semibold">
                {{ session('success') }}
            </span>

        </div>

    </div>
@endif


@if (session('error'))
    <div
        x-data="{ show: true }"
        x-init="
            setTimeout(() => show = false, 3000)
        "
        x-show="show"
        x-transition
        class="fixed left-1/2 top-8 z-9999 -translate-x-1/2"
        style="display:none;"
    >

        <div class="flex items-center gap-3 rounded-xl bg-red-100 border border-red-400 text-red-700 px-6 py-4 shadow-2xl">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>

            </svg>

            <span class="font-semibold">
                {{ session('error') }}
            </span>

        </div>

    </div>
@endif