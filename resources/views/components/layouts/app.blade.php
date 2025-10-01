<x-layouts.app.header :title="$title ?? null">
    <flux:main>
        {{-- ✅ Success Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-700" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586
                         7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934
                         2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1
                         0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0
                         0-1.414z" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- ❌ Error Message --}}
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-700" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586
                         7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934
                         2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1
                         0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0
                         0-1.414z" />
                    </svg>
                </button>
            </div>
        @endif
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
