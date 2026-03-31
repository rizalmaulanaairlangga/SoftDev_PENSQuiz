<header class="w-full flex items-center gap-3 flex-nowrap mb-4">

    <!-- Search -->
    <div class="flex-1 min-w-0">
        <div class="max-w-full flex h-[64px] sm:h-[72px] lg:h-[76px] items-center rounded-[20px] bg-white px-5 shadow-[0_8px_16px_-4px_rgba(0,0,0,0.15)]">
            <img
                src="{{ asset('assets/dashboard/icons/img_search.png') }}"
                alt="Search"
                class="h-6 w-6 shrink-0 sm:h-7 sm:w-7 lg:h-8 lg:w-8"
            >
            <input
                type="text"
                placeholder="Search"
                class="ml-4 w-full border-0 bg-transparent p-0 text-[16px] text-black outline-none placeholder:text-[#9a9a9a]"
            >
        </div>
    </div>

    <!-- Right actions -->
    <div class="flex h-[64px] items-stretch gap-2 sm:h-[72px] lg:h-[76px] lg:gap-3">

        <!-- Settings -->
        <button
            class="h-full w-[64px] items-center justify-center rounded-[20px] bg-white transition hover:bg-gray-50 sm:w-[72px] lg:w-[76px] hidden sm:flex shadow-[0_8px_16px_-4px_rgba(0,0,0,0.15)]">
            <img src="{{ asset('assets/dashboard/icons/img_setting.png') }}" class="h-6 w-6">
        </button>

        <!-- Notifications -->
        <button
            class="flex h-full w-[64px] items-center justify-center rounded-[20px] bg-white shadow-[0_8px_16px_-4px_rgba(0,0,0,0.15)] transition hover:bg-gray-50 sm:w-[72px] lg:w-[76px]">
            <img src="{{ asset('assets/dashboard/icons/img_notif.png') }}" class="h-6 w-6">
        </button>

        <!-- User -->
        <div class="flex h-full items-center gap-3 rounded-[20px] bg-white px-3 shadow-[0_8px_16px_-4px_rgba(0,0,0,0.15)] shrink-0">

            <!-- TEXT -->
            <div class="hidden sm:flex flex-col text-right min-w-0">
                <div class="truncate text-[16px] font-medium">
                    {{ auth()->user()->full_name }}
                </div>
                <div class="truncate text-[14px] text-black/70">
                    {{ auth()->user()->nrp }}
                </div>
            </div>

            <!-- AVATAR -->
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#e6ec6a] text-[20px]">
                {{ strtoupper(substr(auth()->user()->full_name ?? 'U', 0, 1)) }}
            </div>
        </div>

    </div>

</header>