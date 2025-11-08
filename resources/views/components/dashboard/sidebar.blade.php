<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('dashboard') }}">
                        <h4 class="mb-0">Clinic Queue</h4>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                {{-- Dashboard --}}
                @can('view.dashboard.v1')
                <li class="sidebar-item {{ request()->routeIs('dashboard.v1') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.v1') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endcan

                @can('view.dashboard.v2')
                <li class="sidebar-item {{ request()->routeIs('dashboard.v2') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.v2') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endcan

                @can('view.dashboard.v3')
                <li class="sidebar-item {{ request()->routeIs('dashboard.v3') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.v3') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endcan

                {{-- Booking Section --}}
                @canany(['booking.create', 'booking.view.own', 'booking.view.all'])
                <li class="sidebar-title">Booking</li>
                @endcanany

                @can('booking.create')
                <li class="sidebar-item {{ request()->routeIs('booking.create') ? 'active' : '' }}">
                    <a href="{{ route('booking.create') }}" class='sidebar-link'>
                        <i class="bi bi-calendar-plus"></i>
                        <span>Buat Booking</span>
                    </a>
                </li>
                @endcan

                @can('booking.view.own')
                <li class="sidebar-item {{ request()->routeIs('booking.mine') ? 'active' : '' }}">
                    <a href="{{ route('booking.mine') }}" class='sidebar-link'>
                        <i class="bi bi-list-ul"></i>
                        <span>Booking Saya</span>
                    </a>
                </li>
                @endcan

                @can('booking.view.all')
                <li class="sidebar-item {{ request()->routeIs('booking.index') ? 'active' : '' }}">
                    <a href="{{ route('booking.index') }}" class='sidebar-link'>
                        <i class="bi bi-calendar-check"></i>
                        <span>Semua Booking</span>
                    </a>
                </li>
                @endcan

                {{-- Queue Section --}}
                @canany(['queue.view', 'queue.manage'])
                <li class="sidebar-title">Antrian</li>
                @endcanany

                @can('queue.view')
                <li class="sidebar-item {{ request()->routeIs('queue.index') ? 'active' : '' }}">
                    <a href="{{ route('queue.index') }}" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>Kelola Antrian</span>
                    </a>
                </li>
                @endcan

                {{-- Patient Section --}}
                @can('patient.register')
                <li class="sidebar-title">Pasien</li>
                <li class="sidebar-item {{ request()->routeIs('patient.register') ? 'active' : '' }}">
                    <a href="{{ route('patient.register') }}" class='sidebar-link'>
                        <i class="bi bi-person-plus"></i>
                        <span>Daftar Walk-in</span>
                    </a>
                </li>
                @endcan

                {{-- Schedule Section --}}
                @can('schedule.configure')
                <li class="sidebar-title">Jadwal</li>
                <li class="sidebar-item has-sub {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-calendar3"></i>
                        <span>Manajemen Jadwal</span>
                    </a>
                    <ul class="submenu {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->routeIs('schedules.index') ? 'active' : '' }}">
                            <a href="{{ route('schedules.index') }}">Jadwal Praktik</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('schedules.overrides*') ? 'active' : '' }}">
                            <a href="{{ route('schedules.overrides') }}">Override Jadwal</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('schedules.holidays*') ? 'active' : '' }}">
                            <a href="{{ route('schedules.holidays') }}">Hari Libur</a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Report Section --}}
                @canany(['report.view', 'report.export'])
                <li class="sidebar-title">Laporan</li>
                @endcanany

                @can('report.view')
                <li class="sidebar-item {{ request()->routeIs('report.index') ? 'active' : '' }}">
                    <a href="{{ route('report.index') }}" class='sidebar-link'>
                        <i class="bi bi-bar-chart"></i>
                        <span>Laporan & Statistik</span>
                    </a>
                </li>
                @endcan

                {{-- User Management Section --}}
                @can('user.view')
                <li class="sidebar-title">Manajemen</li>
                <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class='sidebar-link'>
                        <i class="bi bi-shield-check"></i>
                        <span>Role Management</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('permissions.index') }}" class='sidebar-link'>
                        <i class="bi bi-key-fill"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                @endcan

                {{-- Settings & Logout --}}
                <li class="sidebar-title">Akun</li>

                <li class="sidebar-item">
                    <a href="{{ route('profile.edit') }}" class='sidebar-link'>
                        <i class="bi bi-person-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link' onclick="confirmLogout(event)">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
