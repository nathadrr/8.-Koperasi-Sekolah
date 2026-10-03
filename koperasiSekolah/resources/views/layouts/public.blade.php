<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Santa Agnes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FFFFFF; }
        .glass-nav { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .hero-gradient { background: radial-gradient(circle at top right, #fee2e2, transparent), radial-gradient(circle at bottom left, #dcfce7, transparent); }
    </style>
</head>
<body class="hero-gradient min-h-screen">
    <nav class="glass-nav sticky top-0 z-50 px-8 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold text-gray-900 tracking-tighter">St. Agnes <span class="text-blue-600">Coop.</span></a>
        
        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
            <a href="/" class="hover:text-blue-600 transition">Home</a>
            <a href="#" class="hover:text-blue-600 transition">About</a>
            <a href="#" class="hover:text-blue-600 transition">Contact</a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">Admin Panel</a>
                <form method="POST" action="{{ route('logout') }}"> @csrf <button class="text-sm font-medium text-red-500">Logout</button></form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-gray-900 text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-blue-600 transition-all shadow-lg shadow-gray-200">Join Now</a>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
