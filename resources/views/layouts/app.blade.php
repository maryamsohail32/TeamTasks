<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTasks</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #F4F2FF; min-height: 100vh; display: flex; }

        /* Sidebar */
        .sidebar {
            width: 220px; min-height: 100vh; background: #1E1B4B;
            display: flex; flex-direction: column; padding: 0; flex-shrink: 0;
            position: fixed; top: 0; left: 0; bottom: 0;
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            font-size: 18px; font-weight: 600; color: #fff;
            letter-spacing: -0.3px; border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand span { color: #A5B4FC; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .sidebar-label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.08em;
            color: rgba(255,255,255,0.3); text-transform: uppercase;
            padding: 0 8px; margin-bottom: 6px; margin-top: 12px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 8px;
            color: rgba(255,255,255,0.6); font-size: 13px; font-weight: 400;
            text-decoration: none; transition: all 0.15s; margin-bottom: 2px;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1); color: #fff;
        }
        .sidebar-link svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-footer {
            padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px; padding: 8px 10px;
            border-radius: 8px; color: rgba(255,255,255,0.7); font-size: 12px;
        }
        .sidebar-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: linear-gradient(135deg, #818CF8, #6366F1);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0;
        }
        .sidebar-user-name { font-weight: 500; color: #fff; font-size: 12px; line-height: 1.3; }
        .sidebar-user-email { font-size: 10px; color: rgba(255,255,255,0.4); }
        .logout-btn {
            display: block; width: 100%; margin-top: 8px; padding: 8px 10px;
            background: rgba(255,255,255,0.06); border: none; border-radius: 8px;
            color: rgba(255,255,255,0.5); font-size: 12px; cursor: pointer;
            text-align: left; font-family: inherit; transition: all 0.15s;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }

        /* Main */
        .main { margin-left: 220px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; border-bottom: 1px solid #E8E4FF;
            padding: 14px 32px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 10;
        }
        .topbar-title { font-size: 15px; font-weight: 600; color: #1E1B4B; }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .page-content { padding: 32px; flex: 1; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px; border-radius: 9px; font-size: 13px;
            font-weight: 500; cursor: pointer; text-decoration: none;
            transition: all 0.15s; border: none; font-family: inherit;
        }
        .btn-primary { background: #4F46E5; color: #fff; }
        .btn-primary:hover { background: #4338CA; }
        .btn-ghost { background: transparent; color: #6B7280; border: 1px solid #E5E7EB; }
        .btn-ghost:hover { background: #F9FAFB; }

        /* Cards */
        .card {
            background: #fff; border-radius: 14px;
            border: 1px solid #EDE9FE; padding: 24px;
        }

        /* Flash */
        .flash-success {
            background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;
            border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px;
        }
        .flash-error {
            background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA;
            border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px;
        }
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-brand">Team<span>Tasks</span></div>
    <nav class="sidebar-nav">
        <div class="sidebar-label">Workspace</div>
        <a href="{{ route('teams.index') }}" class="sidebar-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            Teams
        </a>
    </nav>
    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">→ Sign out</button>
        </form>
        @endauth
    </div>
</aside>

{{-- Main --}}
<div class="main">
    <div class="page-content">
        @if(session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

</body>
</html>