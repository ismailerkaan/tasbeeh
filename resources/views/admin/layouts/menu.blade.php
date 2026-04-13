@php($dashboardActive = request()->routeIs('admin.dashboard'))
@php($versionsActive = request()->routeIs('admin.content-versions.*'))
@php($zikirCategoriesActive = request()->routeIs('admin.zikir-categories.*'))
@php($zikirsActive = request()->routeIs('admin.zikirs.*'))
@php($duaCategoriesActive = request()->routeIs('admin.dua-categories.*'))
@php($duasActive = request()->routeIs('admin.duas.*'))
@php($pushNotificationsActive = request()->routeIs('admin.push-notifications.*'))
@php($mobileUsersActive = request()->routeIs('admin.mobile-users.*'))
@php($mobileFeedbacksActive = request()->routeIs('admin.mobile-feedbacks.*'))
@php($dailyZikrsActive = request()->routeIs('admin.daily-zikrs.*'))

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <span class="brand-logo">
                        <img src="{{ asset('assets/images/logos.png') }}" alt="Tasbeeh App Logo" style="height: 30px; width: 30px; object-fit: contain;">
                    </span>
                    <h2 class="brand-text mb-0">{{ config('app.name', 'Tasbeeh App') }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ $dashboardActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ $versionsActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.content-versions.index') }}">
                    <i data-feather="refresh-cw"></i>
                    <span class="menu-title text-truncate">Versiyon Yönetimi</span>
                </a>
            </li>
            <li class="nav-item {{ $zikirCategoriesActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.zikir-categories.index') }}">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate">Zikir Kategorileri</span>
                </a>
            </li>
            <li class="nav-item {{ $zikirsActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.zikirs.index') }}">
                    <i data-feather="book-open"></i>
                    <span class="menu-title text-truncate">Zikirler</span>
                </a>
            </li>
            <li class="nav-item {{ $duaCategoriesActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.dua-categories.index') }}">
                    <i data-feather="bookmark"></i>
                    <span class="menu-title text-truncate">Dua Kategorileri</span>
                </a>
            </li>
            <li class="nav-item {{ $duasActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.duas.index') }}">
                    <i data-feather="heart"></i>
                    <span class="menu-title text-truncate">Dualar</span>
                </a>
            </li>
            <li class="nav-item {{ $pushNotificationsActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.push-notifications.index') }}">
                    <i data-feather="send"></i>
                    <span class="menu-title text-truncate">Bildirimler</span>
                </a>
            </li>
            <li class="nav-item {{ $mobileUsersActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.mobile-users.index') }}">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate">Kullanıcılar</span>
                </a>
            </li>
            <li class="nav-item {{ $mobileFeedbacksActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.mobile-feedbacks.index') }}">
                    <i data-feather="message-square"></i>
                    <span class="menu-title text-truncate">Geri Bildirimler</span>
                </a>
            </li>
            <li class="nav-item {{ $dailyZikrsActive ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.daily-zikrs.index') }}">
                    <i data-feather="calendar"></i>
                    <span class="menu-title text-truncate">Gunun Zikri</span>
                </a>
            </li>
        </ul>
    </div>
</div>
