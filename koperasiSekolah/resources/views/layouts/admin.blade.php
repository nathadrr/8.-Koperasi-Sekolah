<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Koperasi St. Agnes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen md:flex">
        <aside class="flex w-full shrink-0 flex-col border-b border-slate-200 bg-white md:sticky md:top-0 md:h-screen md:w-64 md:border-b-0 md:border-r">
            <a href="{{ route('admin.dashboard') }}" class="flex h-16 items-center gap-3 border-b border-slate-100 px-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white">K</span>
                <span>
                    <span class="block text-sm font-bold text-slate-900">Koperasi</span>
                    <span class="block text-xs text-slate-500">Panel Admin</span>
                </span>
            </a>

            <nav class="flex flex-1 gap-1 overflow-x-auto p-3 md:block md:space-y-1 md:overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" @class([
                    'flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-blue-50 text-blue-700' => request()->routeIs('admin.dashboard'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !request()->routeIs('admin.dashboard'),
                ])>
                    <span aria-hidden="true">⌂</span> Dashboard
                </a>
                <a href="{{ route('admin.inventory.index') }}" @class([
                    'flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-blue-50 text-blue-700' => request()->routeIs('admin.inventory.*'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !request()->routeIs('admin.inventory.*'),
                ])>
                    <span aria-hidden="true">▦</span> Produk
                </a>
                <a href="{{ route('admin.categories.index') }}" @class([
                    'flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-blue-50 text-blue-700' => request()->routeIs('admin.categories.*'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !request()->routeIs('admin.categories.*'),
                ])>
                    <span aria-hidden="true">▤</span> Kategori
                </a>
                <a href="{{ route('admin.reports') }}" @class([
                    'flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-blue-50 text-blue-700' => request()->routeIs('admin.reports'),
                    'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !request()->routeIs('admin.reports'),
                ])>
                    <span aria-hidden="true">▥</span> Transaksi
                </a>
            </nav>

            <div class="hidden border-t border-slate-100 p-3 md:block">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="sticky top-0 z-10 flex min-h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs font-medium text-slate-500">Koperasi SMPK St. Agnes</p>
                    <h1 class="text-base font-semibold text-slate-900 sm:text-lg">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-slate-600 sm:inline">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                        @csrf
                        <button type="submit" class="rounded-lg border border-rose-100 px-3 py-2 text-xs font-semibold text-rose-600">Keluar</button>
                    </form>
                    @yield('header-actions')
                </div>
            </header>

            <div class="mx-auto w-full max-w-7xl p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                        <p class="font-semibold">Periksa kembali data yang dimasukkan.</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
