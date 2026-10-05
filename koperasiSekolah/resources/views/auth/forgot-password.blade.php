@extends('layouts.public')

@section('content')

<section class="min-h-[80vh] px-8 py-20 flex items-center justify-center">


<div class="w-full max-w-md">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tighter mb-4">
            Reset Your <span class="text-blue-600">Password</span>
        </h1>

        <p class="text-gray-500 leading-relaxed">
            Masukkan email yang terdaftar untuk mendapatkan link reset password.
        </p>
    </div>

    <!-- SESSION STATUS -->
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <!-- RESET PASSWORD CARD -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-xl p-8">

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- EMAIL -->
            <div>
                <label
                    for="email"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
                    placeholder="Enter your email"
                >

                @error('email')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="w-full mt-7 py-4 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-blue-600 transition-all shadow-lg"
            >
                Send Password Reset Link
            </button>

        </form>

        <!-- BACK TO LOGIN -->
        <div class="text-center mt-7 pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">
                Remember your password?
                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-blue-600 hover:text-blue-700 transition"
                >
                    Back to Login
                </a>
            </p>
        </div>

    </div>

</div>


</section>

@endsection
