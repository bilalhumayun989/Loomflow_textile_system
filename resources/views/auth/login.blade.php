<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LoomFlow Textile ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 20%, #1e1b4b 0%, #0f172a 40%, #020617 100%);
        }
        .loom-pattern {
            background-image: radial-gradient(rgba(99, 102, 241, 0.15) 1px, transparent 1px), radial-gradient(rgba(6, 182, 212, 0.12) 1px, transparent 1px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 loom-pattern">

    <div class="max-w-md w-full">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 via-cyan-400 to-emerald-400 p-0.5 shadow-2xl shadow-cyan-500/30 mb-4 transform hover:scale-105 transition-transform">
                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                    <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">LoomFlow</h1>
            <p class="text-xs uppercase tracking-widest font-semibold text-cyan-400 mt-1">Textile Weaving & Mill Management</p>
            <p class="text-sm text-slate-400 mt-2">Sign in to control your loom production, bags & stock</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/80">
            
            @if(session('error'))
                <div class="mb-5 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-5 p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </div>
                        <input type="email" name="email" id="email" required value="{{ old('email', 'admin@loomflow.com') }}" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" placeholder="user@loomflow.com">
                    </div>
                    @error('email')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" name="password" id="password" required value="password" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-200">
                        <input type="checkbox" name="remember" checked class="rounded border-slate-700 bg-slate-950 text-cyan-500 focus:ring-cyan-500/50">
                        <span>Remember credentials</span>
                    </label>
                    <span class="text-slate-500 font-mono text-[11px]">Pass: password</span>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-cyan-500 to-emerald-500 hover:from-indigo-500 hover:to-emerald-400 text-white font-bold text-sm shadow-lg shadow-cyan-500/25 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2">
                    <span>Sign In to Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- 1-Click Quick Login Demo Section -->
            <div class="mt-8 pt-6 border-t border-slate-800">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3 text-center">⚡ Instant 1-Click Demo Login</p>
                <div class="grid grid-cols-3 gap-2">
                    <form action="{{ route('quick-login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="admin@loomflow.com">
                        <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-800/80 hover:bg-indigo-600/20 border border-slate-700/70 hover:border-indigo-500/50 text-center transition-all group">
                            <span class="block text-xs font-bold text-slate-200 group-hover:text-indigo-300">Admin</span>
                            <span class="block text-[9px] text-slate-500">Full Access</span>
                        </button>
                    </form>

                    <form action="{{ route('quick-login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="store@loomflow.com">
                        <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-800/80 hover:bg-amber-600/20 border border-slate-700/70 hover:border-amber-500/50 text-center transition-all group">
                            <span class="block text-xs font-bold text-slate-200 group-hover:text-amber-300">Store</span>
                            <span class="block text-[9px] text-slate-500">Bags & Parts</span>
                        </button>
                    </form>

                    <form action="{{ route('quick-login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="master@loomflow.com">
                        <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-800/80 hover:bg-rose-600/20 border border-slate-700/70 hover:border-rose-500/50 text-center transition-all group">
                            <span class="block text-xs font-bold text-slate-200 group-hover:text-rose-300">Master</span>
                            <span class="block text-[9px] text-slate-500">Looms & Shift</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <div class="text-center mt-6 text-xs text-slate-500 font-mono">
            MySQL: <strong class="text-emerald-400">looming</strong> &bull; Engine: Active
        </div>
    </div>

</body>
</html>
