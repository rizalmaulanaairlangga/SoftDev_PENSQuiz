<x-app-layout>
    @php
        $userLabel = $user?->first_name ?: 'User';
        $userInitial = strtoupper(substr($userLabel, 0, 1));
    @endphp

    <div 
        x-data="{ 
            // ==========================================
            // STATE MANAGEMENT
            // ==========================================
            tab: '{{ $errors->updatePassword->any() || session('status') === 'password-updated' ? 'login' : 'personal' }}',
            mobileMenuOpen: false,
            
            // ==========================================
            // SEMESTER CALCULATION
            // ==========================================
            year_of_entry: {{ $user->year_of_entry ?? date('Y') }},
            get currentSemester() {
                const currentYear = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1;
                const entry = parseInt(this.year_of_entry) || currentYear;
                let sem = (currentYear - entry) * 2 + (currentMonth >= 8 ? 1 : 0);
                return Math.max(1, sem);
            }
        }" 
        class="min-h-[calc(100vh-200px)] flex flex-col lg:flex-row gap-6 sm:gap-8"
    >
        <!-- ========================================== -->
        <!-- SIDEBAR NAVIGATION -->
        <!-- ========================================== -->
        <aside class="w-full lg:w-80 shrink-0">
            <div class="bg-white rounded-[32px] p-6 sm:p-8 shadow-sm border border-gray-100 h-fit">
                
                <!-- Avatar Section (Synced with Top Nav) -->
                <div class="relative w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-8 sm:mb-10 lg:block hidden">
                    <div class="w-full h-full rounded-full bg-[#fdc02a] flex items-center justify-center border-4 border-white shadow-lg text-4xl sm:text-5xl font-extrabold text-[#17426a]">
                        {{ $userInitial }}
                    </div>
                </div>

                <!-- Avatar & Tab Info for Mobile Header (Compact) -->
                <div class="lg:hidden flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-full bg-[#fdc02a] flex items-center justify-center border-2 border-white shadow-md text-2xl font-extrabold text-[#17426a]">
                        {{ $userInitial }}
                    </div>
                    <div>
                        <p class="text-lg font-black text-slate-900 leading-tight">{{ $userLabel }}</p>
                        <p class="text-sm font-bold text-[#528FB9]" x-text="tab === 'personal' ? 'Personal Information' : 'Login Information'"></p>
                    </div>
                </div>

                <!-- Mobile Dropdown Navigation -->
                <div class="lg:hidden relative mb-4">
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="w-full flex items-center justify-between px-6 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl font-bold transition hover:bg-gray-100"
                    >
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-lg bg-[#528FB9] text-white flex items-center justify-center">
                                <template x-if="tab === 'personal'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </template>
                                <template x-if="tab === 'login'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </template>
                            </div>
                            <span class="text-slate-700" x-text="tab === 'personal' ? 'Personal Info' : 'Login Info'"></span>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="mobileMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div 
                        x-show="mobileMenuOpen" 
                        x-cloak
                        @click.away="mobileMenuOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute top-full left-0 right-0 mt-3 bg-white border border-slate-100 rounded-[24px] shadow-[0_20px_50px_rgba(15,23,42,0.15)] z-[60] p-2 space-y-1"
                    >
                        <button 
                            @click="tab = 'personal'; mobileMenuOpen = false"
                            class="w-full flex items-center gap-4 px-5 py-4 rounded-xl font-bold transition"
                            :class="tab === 'personal' ? 'bg-[#eef8fc] text-[#528FB9]' : 'text-slate-600 hover:bg-slate-50'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Personal Info
                        </button>
                        <button 
                            @click="tab = 'login'; mobileMenuOpen = false"
                            class="w-full flex items-center gap-4 px-5 py-4 rounded-xl font-bold transition"
                            :class="tab === 'login' ? 'bg-[#eef8fc] text-[#528FB9]' : 'text-slate-600 hover:bg-slate-50'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Login Info
                        </button>
                        <div class="h-px bg-slate-100 my-2 mx-4"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-4 px-5 py-4 rounded-xl font-bold text-red-500 hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Desktop Sidebar Navigation -->
                <div class="hidden lg:block space-y-4">
                    <button 
                        @click="tab = 'personal'"
                        :class="tab === 'personal' ? 'bg-[#528FB9] text-white' : 'bg-white text-black border border-gray-100 hover:bg-[#eef8fc] hover:border-[#528FB9]/50'"
                        class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition duration-200"
                    >
                        <div :class="tab === 'personal' ? 'bg-white text-[#528FB9]' : 'bg-gray-50 text-black'" class="w-10 h-10 rounded-xl flex items-center justify-center transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        Personal Info
                    </button>
                    
                    <button 
                        @click="tab = 'login'"
                        :class="tab === 'login' ? 'bg-[#528FB9] text-white' : 'bg-white text-black border border-gray-100 hover:bg-[#eef8fc] hover:border-[#528FB9]/50'"
                        class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl font-bold transition duration-200"
                    >
                        <div :class="tab === 'login' ? 'bg-white text-[#528FB9]' : 'bg-gray-50 text-black'" class="w-10 h-10 rounded-xl flex items-center justify-center transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        Login Info
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="pt-4">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl font-bold text-black transition duration-200 hover:bg-red-50 hover:text-red-500 group"
                        >
                            <div class="w-10 h-10 bg-white border border-gray-100 text-red-500 rounded-xl flex items-center justify-center group-hover:border-red-200 transition shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- MAIN CONTENT AREA -->
        <!-- ========================================== -->
        <main class="flex-1">
            
            <!-- PERSONAL INFO TAB -->
            <div x-show="tab === 'personal'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-[40px] p-8 sm:p-12 shadow-sm border border-gray-100">
                    <h2 class="text-2xl sm:text-3xl font-black text-black mb-8 sm:mb-10">Personal Information</h2>
                    
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6 sm:space-y-8">
                        @csrf
                        @method('patch')

                        @if ($errors->any() && !$errors->updatePassword->any())
                            <div class="p-4 sm:p-6 bg-red-50 border border-red-100 rounded-2xl">
                                <div class="text-sm font-bold text-red-600 mb-2">Please correct the following errors:</div>
                                <ul class="list-disc list-inside text-xs text-red-500 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Student Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Major</label>
                            <select name="major_id" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition appearance-none cursor-pointer">
                                <option value="">Select Major</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id_major }}" {{ old('major_id', $user->major_id) == $major->id_major ? 'selected' : '' }}>{{ $major->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('major_id')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Semester</label>
                                <input type="number" readonly :value="currentSemester" class="w-full bg-gray-50/50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-[#528FB9] cursor-not-allowed">
                                <p class="mt-2 text-xs text-gray-400">Calculated automatically</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Year of Entry</label>
                                <input type="number" name="year_of_entry" x-model="year_of_entry" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                                <x-input-error class="mt-2" :messages="$errors->get('year_of_entry')" />
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 pt-6 sm:pt-10">
                            <button type="reset" class="w-full sm:w-1/2 bg-white border border-gray-200 text-black py-4 sm:py-5 rounded-[24px] text-base sm:text-lg font-black hover:bg-[#eef8fc] hover:border-[#528FB9]/30 transition order-2 sm:order-1">Discard Changes</button>
                            <button type="submit" class="w-full sm:w-1/2 bg-[#528FB9] text-white py-4 sm:py-5 rounded-[24px] text-base sm:text-lg font-black hover:bg-[#17426A] transition shadow-lg shadow-blue-900/10 order-1 sm:order-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- LOGIN INFO TAB -->
            <div x-show="tab === 'login'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-[40px] p-8 sm:p-12 shadow-sm border border-gray-100">
                    <h2 class="text-2xl sm:text-3xl font-black text-black mb-4">Login Information</h2>
                    
                    <div class="mb-8 sm:mb-10">
                        <label class="block text-sm font-bold text-black mb-3">Student Email</label>
                        <div class="w-full bg-gray-50/50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-500 overflow-hidden text-ellipsis">
                            {{ $user->email }}
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-black mb-6">Change Password</h3>
                    
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6 sm:space-y-8">
                        @csrf
                        @method('put')

                        @if ($errors->updatePassword->any())
                            <div class="p-4 sm:p-6 bg-red-50 border border-red-100 rounded-2xl">
                                <div class="text-sm font-bold text-red-600 mb-2">Password update failed:</div>
                                <ul class="list-disc list-inside text-xs text-red-500 space-y-1">
                                    @foreach ($errors->updatePassword->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Current Password</label>
                            <input type="password" name="current_password" placeholder="Current password" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">New Password</label>
                                <input type="password" name="password" placeholder="New password" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Confirm New Password</label>
                                <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full bg-gray-50 border border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-black focus:bg-white focus:border-[#528FB9] focus:outline-none transition">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 pt-6 sm:pt-10">
                            <button type="reset" class="w-full sm:w-1/2 bg-white border border-gray-200 text-black py-4 sm:py-5 rounded-[24px] text-base sm:text-lg font-black hover:bg-[#eef8fc] hover:border-[#528FB9]/30 transition order-2 sm:order-1">Discard Changes</button>
                            <button type="submit" class="w-full sm:w-1/2 bg-[#17426A] text-white py-4 sm:py-5 rounded-[24px] text-base sm:text-lg font-black hover:bg-[#17426A] transition shadow-lg shadow-blue-900/10 order-1 sm:order-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 bg-green-500 text-white px-6 py-3 sm:px-8 sm:py-4 rounded-2xl text-sm sm:text-base font-bold shadow-2xl z-[100]"
        >
            Changes saved successfully!
        </div>
    @endif
</x-app-layout>
