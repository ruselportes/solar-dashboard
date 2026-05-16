<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Solar Charging Station — Real-time monitoring dashboard">
    <title>@yield('title', 'Solar Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      /* ── Global Reset & Dark Theme ─────────────────────── */
      *, *::before, *::after { box-sizing: border-box; }

      :root {
        --bg-primary:    #0b0f1a;
        --bg-secondary:  #111827;
        --bg-card:       rgba(17, 24, 39, 0.7);
        --bg-card-hover: rgba(23, 32, 52, 0.85);
        --border-subtle: rgba(255,255,255,0.06);
        --border-glow:   rgba(251,191,36,0.15);
        --text-primary:  #f1f5f9;
        --text-secondary:#94a3b8;
        --text-muted:    #64748b;
        --amber:         #f59e0b;
        --amber-glow:    rgba(245,158,11,0.15);
        --teal:          #06b6d4;
        --teal-glow:     rgba(6,182,212,0.15);
        --violet:        #8b5cf6;
        --violet-glow:   rgba(139,92,246,0.15);
        --emerald:       #10b981;
        --emerald-glow:  rgba(16,185,129,0.15);
        --rose:          #f43f5e;
        --rose-glow:     rgba(244,63,94,0.15);
        --radius:        16px;
        --radius-sm:     10px;
        --glass-blur:    12px;
        --transition:    all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }

      body {
        background: var(--bg-primary);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
      }

      /* Animated background gradient orbs */
      body::before, body::after {
        content: '';
        position: fixed;
        border-radius: 50%;
        filter: blur(120px);
        opacity: 0.15;
        pointer-events: none;
        z-index: 0;
      }
      body::before {
        width: 600px; height: 600px;
        top: -200px; right: -100px;
        background: radial-gradient(circle, var(--amber) 0%, transparent 70%);
        animation: float1 20s ease-in-out infinite;
      }
      body::after {
        width: 500px; height: 500px;
        bottom: -150px; left: -100px;
        background: radial-gradient(circle, var(--teal) 0%, transparent 70%);
        animation: float2 25s ease-in-out infinite;
      }
      @keyframes float1 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-80px, 60px) scale(1.1); }
      }
      @keyframes float2 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(60px, -80px) scale(1.15); }
      }

      /* ── Navbar ─────────────────────────────────────────── */
      .solar-nav {
        background: rgba(11, 15, 26, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border-subtle);
        padding: 0.75rem 0;
        position: sticky;
        top: 0;
        z-index: 100;
      }
      .solar-nav .nav-brand {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        text-decoration: none;
        letter-spacing: -0.02em;
      }
      .solar-nav .nav-brand .brand-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, var(--amber), #f97316);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px var(--amber-glow);
      }
      .nav-links {
        display: flex;
        align-items: center;
        gap: 0.25rem;
      }
      .nav-links a {
        color: var(--text-secondary);
        text-decoration: none;
        padding: 0.45rem 1rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 500;
        transition: var(--transition);
      }
      .nav-links a:hover { color: var(--text-primary); background: rgba(255,255,255,0.05); }
      .nav-links a.active { color: var(--amber); background: var(--amber-glow); }

      .nav-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
        color: var(--text-muted);
      }
      .nav-status .status-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--emerald);
        box-shadow: 0 0 8px var(--emerald);
        animation: statusPulse 2s ease-in-out infinite;
      }
      @keyframes statusPulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 8px var(--emerald); }
        50% { opacity: 0.5; box-shadow: 0 0 16px var(--emerald); }
      }

      /* ── Page wrapper ───────────────────────────────────── */
      main { position: relative; z-index: 1; }

      /* ── Scrollbar ──────────────────────────────────────── */
      ::-webkit-scrollbar { width: 6px; }
      ::-webkit-scrollbar-track { background: transparent; }
      ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
      ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="solar-nav">
        <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="nav-brand">
                <span class="brand-icon">☀️</span>
                Solar Station
            </a>
            <div class="nav-links d-none d-md-flex">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('logs') }}" class="{{ request()->routeIs('logs') ? 'active' : '' }}">
                    📋 Sensor Logs
                </a>
            </div>
            <div class="nav-status">
                <span class="status-dot"></span>
                <span id="nav-connection-status">Connected</span>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>