<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} — Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>[x-cloak] { display: none !important; }</style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { font-family: 'Plus Jakarta Sans', 'Figtree', system-ui, sans-serif; }
        body { background: #F8FAFC; }

        /* Sidebar */
        .sidebar { width: 240px; min-height: 100vh; background: #fff; border-right: 1px solid #F1F2F6; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 40; transition: transform 0.3s ease; }
        .sidebar-logo { padding: 24px 20px 12px; display: flex; align-items: center; gap: 12px; }
        .sidebar-logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #5b21b6, #7c3aed); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .sidebar-logo-text { font-size: 0.7rem; font-weight: 800; color: #1e1b4b; line-height: 1.25; text-transform: uppercase; letter-spacing: 0.02em; }
        .sidebar-logo-sub { font-size: 0.6rem; font-weight: 500; color: #94a3b8; margin-top: 2px; text-transform: none; letter-spacing: 0; }
        .sidebar-cta { margin: 16px 16px 8px; }
        .sidebar-cta button { width: 100%; padding: 10px 14px; background: linear-gradient(135deg, #5b21b6, #7c3aed); color: #fff; font-size: 0.8rem; font-weight: 700; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: opacity 0.2s; }
        .sidebar-cta button:hover { opacity: 0.9; }
        .sidebar-nav { flex: 1; padding: 8px 0; }
        .sidebar-nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 20px; font-size: 0.82rem; font-weight: 500; color: #64748b; text-decoration: none; transition: all 0.15s; position: relative; }
        .sidebar-nav-item:hover { color: #1e1b4b; background: #f8f7ff; }
        .sidebar-nav-item.active { color: #5b21b6; font-weight: 700; background: #f5f3ff; }
        .sidebar-nav-item.active::before { content: ''; position: absolute; left: 0; top: 6px; bottom: 6px; width: 3px; background: #7c3aed; border-radius: 0 3px 3px 0; }
        .sidebar-nav-icon { width: 20px; height: 20px; flex-shrink: 0; }
        .sidebar-bottom { border-top: 1px solid #f1f5f9; padding: 8px 0 16px; }
        .sidebar-nav-item[style*="color:#ef4444"]:hover {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #dc2626 !important;
        }

        /* Topbar */
        .topbar { height: 60px; background: #fff; border-bottom: 1px solid #F1F2F6; display: flex; align-items: center; padding: 0 28px; gap: 16px; position: sticky; top: 0; z-index: 30; }
        .topbar-title { font-size: 0.88rem; font-weight: 700; color: #1e1b4b; white-space: nowrap; }
        .topbar-search { flex: 1; max-width: 400px; position: relative; }
        .topbar-search input { width: 100%; padding: 8px 14px 8px 36px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.82rem; color: #334155; outline: none; transition: border-color 0.15s, box-shadow 0.15s; }
        .topbar-search input:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,0.1); }
        .topbar-search input::placeholder { color: #94a3b8; }
        .topbar-search svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #94a3b8; }
        .topbar-actions { display: flex; align-items: center; gap: 6px; margin-left: auto; }
        .topbar-icon-btn { width: 36px; height: 36px; border-radius: 8px; border: none; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #64748b; transition: background 0.15s, color 0.15s; }
        .topbar-icon-btn:hover { background: #f1f5f9; color: #1e1b4b; }
        .topbar-support { font-size: 0.8rem; font-weight: 600; color: #64748b; text-decoration: none; margin: 0 4px; }
        .topbar-support:hover { color: #5b21b6; }
        .topbar-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #5b21b6, #7c3aed); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.75rem; font-weight: 700; cursor: pointer; border: 2px solid #e9e5ff; }

        /* Content area */
        .main-content { margin-left: 240px; min-height: 100vh; }
        .page-content { padding: 28px; background-color: #F8FAFC;}

        /* Mobile overlay */    
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.3); z-index: 35; }

        /* Mobile hamburger */
        .mobile-menu-btn { display: none; width: 36px; height: 36px; border-radius: 8px; border: none; background: transparent; cursor: pointer; color: #64748b; align-items: center; justify-content: center; }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-content { margin-left: 0; }
            .mobile-menu-btn { display: flex; }
        }
    </style>
</head>
<body class="antialiased" x-data="{ sidebarOpen: false }">

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <div class="sidebar-logo" style="display:flex; justify-content:center; align-items:center;">
            <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa HR Consulting" style="height: 60px; width: auto;">
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" wire:navigate>
                <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                Dashboard
            </a>
            <a href="{{ route('questions') }}" class="sidebar-nav-item {{ request()->routeIs('questions') ? 'active' : '' }}" wire:navigate>
                <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2M9 5h6"/></svg>
                Data Pertanyaan
            </a>
            <a href="{{ route('events') }}" class="sidebar-nav-item {{ request()->routeIs('events') ? 'active' : '' }}" wire:navigate>
                <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg>
                Data Event
            </a>
            <a href="{{ route('respondents') }}" class="sidebar-nav-item {{ request()->routeIs('respondents') ? 'active' : '' }}" wire:navigate>
                <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Data Responden
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="#" 
            class="sidebar-nav-item mx-2" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            style="color:#ef4444; background:rgba(239,68,68,0.08); border-radius:8px;">
                <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Logout
            </a>
            <form id="logout-form" method="POST" action="/logout" style="display:none;">@csrf</form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <button class="mobile-menu-btn" @click="sidebarOpen = !sidebarOpen">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div class="topbar-actions">
                <span class="topbar-support">{{ auth()->user()->name ?? 'Admin' }}</span>
                <div class="topbar-avatar" title="{{ auth()->user()->name ?? 'Admin' }}">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth()->user()->name ?? 'D', 1, 1)) }}
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
