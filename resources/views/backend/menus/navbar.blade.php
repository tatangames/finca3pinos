<nav class="main-header navbar navbar-expand border-bottom navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Finca3Pinos</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-cogs"></i> <span class="hidden-xs">{{ $user?->nombre }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{ route('admin.perfil') }}"
                   class="dropdown-item {{ request()->routeIs('admin.perfil') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Editar Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('frm-logout').submit();" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
                <form id="frm-logout" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
