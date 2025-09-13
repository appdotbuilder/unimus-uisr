<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="bi bi-database-fill me-2"></i>
            UISR
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('datasets.*') ? 'active' : '' }}" href="{{ route('datasets.index') }}">
                        <i class="bi bi-folder-fill me-1"></i>
                        Datasets
                    </a>
                </li>
                @if(auth()->user()->role === 'curator' || auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="bi bi-graph-up me-1"></i>
                        Reports
                    </a>
                </li>
                @endif
            </ul>

            <!-- Right Navigation -->
            <ul class="navbar-nav">
                <!-- Theme Switcher -->
                <li class="nav-item dropdown">
                    <button class="nav-link btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-palette"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="setTheme('light')">
                            <i class="bi bi-sun-fill me-2"></i>Light
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="setTheme('dark')">
                            <i class="bi bi-moon-stars-fill me-2"></i>Dark
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="setTheme('auto')">
                            <i class="bi bi-circle-half me-2"></i>System
                        </a></li>
                    </ul>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <h6 class="dropdown-header">
                                {{ auth()->user()->email }}
                                @if(auth()->user()->role)
                                <br><small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                                @endif
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="alert('Settings coming soon')">
                                <i class="bi bi-gear me-2"></i>Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>