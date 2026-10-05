@extends('layouts.public')

@section('content')

<section class="min-h-[80vh] px-8 py-20 flex items-center justify-center">

<div class="w-full max-w-md">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tighter mb-4">
            Create Your <span class="text-blue-600">Account</span>
        </h1>

        <p class="text-gray-500 leading-relaxed">
            Daftar untuk mulai menggunakan layanan Koperasi SMPK St. Agnes Surabaya.
        </p>
    </div>

    <!-- REGISTER CARD -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-xl p-8">

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NAME -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                    placeholder="Enter your name"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div class="mt-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                    placeholder="Enter your email"
                >

                @error('email')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mt-5">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                    placeholder="Enter your password"
                >

                @error('password')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            
            <!-- CONFIRM PASSWORD -->
            <div class="mt-5">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Confirm Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                    placeholder="Confirm your password"
                >

                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Tambahkan ini di atas bagian Confirm Password atau sebelum tombol Register -->
            <div class="mt-4">
                <label for="role" class="block font-medium text-sm text-gray-700">Daftar Sebagai</label>
                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm py-2" required>
                    <option value="student">Siswa / Orang Tua</option>
                    <option value="admin">Administrator</option>
                </select>
                <!-- Pesan Error (jika ada) -->
                @error('role')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            <!-- BUTTON -->
            <button
                type="submit"
                class="w-full mt-7 py-4 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-blue-600 transition-all shadow-lg"
            >
                Create Account
            </button>

        </form>

        <!-- LOGIN -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                Already have an account?
                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-blue-600 hover:text-blue-700 transition"
                >
                    Login
                </a>
            </p>
        </div>

    </div>

</div>


</section>

@endsection
