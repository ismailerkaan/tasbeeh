<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item">
                    <a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a>
                </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item d-none d-lg-block">
                <a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a>
            </li>
            <li class="nav-item">
                <span class="nav-link">{{ now()->format('d.m.Y H:i') }}</span>
            </li>
            <li class="nav-item">
                <span class="nav-link">{{ auth()->user()?->email }}</span>
            </li>
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i data-feather="log-out" class="me-50"></i>Çıkış
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
