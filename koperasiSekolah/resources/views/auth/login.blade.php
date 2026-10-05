@extends('layouts.public')

@section('content')

<section class="min-h-[80vh] px-8 py-20 flex items-center justify-center">

<div class="w-full max-w-md">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tighter mb-4">
            Welcome <span class="text-blue-600">Back</span>
        </h1>

        <p class="text-gray-500 leading-relaxed">
            Login untuk melanjutkan ke Koperasi SMPK St. Agnes Surabaya.
        </p>
    </div>

    <!-- SESSION STATUS -->
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <!-- LOGIN CARD -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-xl p-8">

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
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
                    autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                    placeholder="Enter your password"
                >

                @error('password')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- REMEMBER ME -->
            <div class="mt-5">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">

                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    >

                    <span class="ms-2 text-sm text-gray-500">
                        Remember me
                    </span>

                </label>
            </div>

            <!-- BUTTON + FORGOT PASSWORD -->
            <div class="mt-7">

                <button
                    type="submit"
                    class="w-full py-4 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-blue-600 transition-all shadow-lg"
                >
                    Log In
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-5">
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm text-gray-500 hover:text-blue-600 transition"
                        >
                            Forgot your password?
                        </a>
                    </div>
                @endif

            </div>

        </form>

        <!-- REGISTER -->
        <div class="text-center mt-7 pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Don't have an account?
                <a
                    href="{{ route('register') }}"
                    class="font-semibold text-blue-600 hover:text-blue-700 transition"
                >
                    Create Account
                </a>
            </p>
        </div>

    </div>

</div>

</section>

@endsection
