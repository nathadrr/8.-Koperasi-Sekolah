<x-guest-layout>
    <div class="min-h-[calc(100vh-4rem)] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white/70 backdrop-blur-md p-8 rounded-3xl border border-white shadow-xl">
            
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Daftar Akun Baru</h2>
                <p class="text-xs text-gray-500 mt-2">Buat akun untuk mengakses layanan Koperasi SMPK St. Agnes</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full px-4 py-2.5 text-sm bg-white/90 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                           placeholder="Masukkan nama lengkap">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="w-full px-4 py-2.5 text-sm bg-white/90 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                           placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-2.5 text-sm bg-white/90 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-4 py-2.5 text-sm bg-white/90 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-md">
                        Daftar Akun
                    </button>
                </div>

                <div class="text-center pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>