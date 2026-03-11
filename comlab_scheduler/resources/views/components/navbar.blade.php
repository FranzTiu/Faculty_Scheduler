{{--
navbar.blade.php
----------------
Reusable navigation bar component included on every authenticated page.
- Fixed top header that stays visible when scrolling.
- Layout: Logo+Brand (left) | Nav Links (center) | Clock+Avatar (right)
- Responsive: collapses to hamburger/drawer on mobile & tablet.
- Active page is highlighted with a yellow underline.
- Uses the current Laravel route to auto-detect which nav link is active.
--}}
<nav id="mainNavbar" class="navbar-fixed">
    <div class="navbar-inner">

        {{-- ═══ LEFT: Logo + Brand ═══ --}}
        <div class="navbar-left">
            <div class="navbar-brand">
                <img src="{{ asset('LNU_Logo.png') }}"
                    onerror="this.onerror=null;this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 60 60\'><circle cx=\'30\' cy=\'30\' r=\'28\' fill=\'%231e1b4b\' stroke=\'%23fbbf24\' stroke-width=\'3\'/><text x=\'30\' y=\'35\' text-anchor=\'middle\' fill=\'white\' font-size=\'14\' font-family=\'serif\' font-weight=\'bold\'>LNU</text></svg>'"
                    alt="LNU Logo" class="navbar-logo">
                <div class="navbar-title-group">
                    <span class="navbar-title-main">IT Faculty</span>
                    <span class="navbar-title-sub">
                        <span class="navy">ComLab</span>
                        <span class="gold"> Scheduler</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- ═══ CENTER: Navigation Links (desktop) ═══ --}}
        <div class="navbar-center">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Home
            </a>
            <a href="{{ route('schedules') }}" class="nav-link {{ request()->routeIs('schedules') ? 'active' : '' }}">
                Schedules
            </a>
            <a href="{{ route('teachers') }}" class="nav-link {{ request()->routeIs('teachers') ? 'active' : '' }}">
                Teachers
            </a>
            <a href="{{ route('comlabs_subjects') }}"
                class="nav-link {{ request()->routeIs('comlabs_subjects') ? 'active' : '' }}">
                ComLabs &amp; Subjects
            </a>
        </div>

        {{-- ═══ RIGHT: Clock + User Avatar + Dropdown ═══ --}}
        <div class="navbar-right">
            {{-- Digital Clock --}}
            <div id="digitalClock" class="navbar-clock">
                --:--:--
            </div>

            {{-- User Profile Avatar --}}
            <div class="relative">
                <div id="userProfileBtn" onclick="toggleUserMenu()" class="navbar-avatar">
                    {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                {{-- Dropdown --}}
                <div id="userDropdownMenu" class="navbar-dropdown hidden">
                    <div class="navbar-dropdown-header">
                        <p class="navbar-dropdown-label">Logged in as</p>
                        <p class="navbar-dropdown-name">
                            {{ auth()->user()->username ?? auth()->user()->name ?? 'Admin' }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="navbar-dropdown-logout">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Dropdown arrow --}}
            <span onclick="toggleUserMenu()" class="navbar-dropdown-arrow">▼</span>
        </div>

        {{-- ═══ HAMBURGER BUTTON (mobile/tablet only) ═══ --}}
        <button id="hamburgerBtn" class="hamburger-btn" onclick="toggleMobileDrawer()" aria-label="Open menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

    </div>
</nav>

{{-- ═══ MOBILE DRAWER (off-canvas sidebar) ═══ --}}
<div id="drawerOverlay" class="drawer-overlay" onclick="closeMobileDrawer()"></div>
<aside id="mobileDrawer" class="mobile-drawer">
    {{-- Drawer Header --}}
    <div class="drawer-header">
        <img src="{{ asset('LNU_Logo.png') }}"
            onerror="this.onerror=null;this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 60 60\'><circle cx=\'30\' cy=\'30\' r=\'28\' fill=\'%231e1b4b\' stroke=\'%23fbbf24\' stroke-width=\'3\'/><text x=\'30\' y=\'35\' text-anchor=\'middle\' fill=\'white\' font-size=\'14\' font-family=\'serif\' font-weight=\'bold\'>LNU</text></svg>'"
            alt="LNU Logo" class="drawer-logo">
        <div class="drawer-title-group">
            <span class="drawer-title-main">IT Faculty</span>
            <span class="drawer-title-sub">
                <span class="navy">ComLab</span>
                <span class="gold"> Scheduler</span>
            </span>
        </div>
        <button class="drawer-close-btn" onclick="closeMobileDrawer()" aria-label="Close menu">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
    </div>

    {{-- Drawer Nav Links --}}
    <nav class="drawer-nav">
        <a href="{{ route('dashboard') }}" class="drawer-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Home
        </a>
        <a href="{{ route('schedules') }}" class="drawer-link {{ request()->routeIs('schedules') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Schedules
        </a>
        <a href="{{ route('teachers') }}" class="drawer-link {{ request()->routeIs('teachers') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Teachers
        </a>
        <a href="{{ route('comlabs_subjects') }}"
            class="drawer-link {{ request()->routeIs('comlabs_subjects') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                <line x1="8" y1="21" x2="16" y2="21" />
                <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
            ComLabs &amp; Subjects
        </a>
    </nav>

    {{-- Drawer Footer --}}
    <div class="drawer-footer">
        <div class="drawer-user-info">
            <div class="drawer-user-avatar">
                {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="drawer-user-details">
                <span class="drawer-user-name">{{ auth()->user()->username ?? auth()->user()->name ?? 'Admin' }}</span>
                <span class="drawer-user-role">Administrator</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="drawer-logout-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<script>
    // ── User dropdown toggle ──
    function toggleUserMenu() {
        const menu = document.getElementById('userDropdownMenu');
        if (menu) menu.classList.toggle('hidden');
    }

    // Close dropdown if clicking outside
    document.addEventListener('click', function (e) {
        const btn = document.getElementById('userProfileBtn');
        const menu = document.getElementById('userDropdownMenu');
        const arrow = e.target.closest('.navbar-dropdown-arrow');
        if (!arrow && btn && menu && !btn.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // ── Mobile Drawer Toggle ──
    function toggleMobileDrawer() {
        const drawer = document.getElementById('mobileDrawer');
        const overlay = document.getElementById('drawerOverlay');
        const hamburger = document.getElementById('hamburgerBtn');
        const isOpen = drawer.classList.contains('open');

        if (isOpen) {
            closeMobileDrawer();
        } else {
            drawer.classList.add('open');
            overlay.classList.add('open');
            hamburger.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMobileDrawer() {
        const drawer = document.getElementById('mobileDrawer');
        const overlay = document.getElementById('drawerOverlay');
        const hamburger = document.getElementById('hamburgerBtn');
        drawer.classList.remove('open');
        overlay.classList.remove('open');
        hamburger.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close drawer on window resize to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth > 1024) {
            closeMobileDrawer();
        }
    });

    // ── Digital Clock ──
    (function startClock() {
        const clock = document.getElementById('digitalClock');
        if (!clock) return;
        function tick() {
            clock.textContent = new Date().toLocaleTimeString([], {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        }
        tick();
        setInterval(tick, 1000);
    })();

    // ── Navbar shadow on scroll ──
    window.addEventListener('scroll', function () {
        const navbar = document.getElementById('mainNavbar');
        if (!navbar) return;
        if (window.scrollY > 10) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>