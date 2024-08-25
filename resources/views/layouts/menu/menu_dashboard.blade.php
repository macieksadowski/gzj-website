<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center justify-content-lg-between">

        <a href="index.html" class="logo me-auto me-lg-0"><img src="{{ asset('/img/logo-header.gif') }}"
                alt="Logo Główny Zawór Jazzu" class="img-fluid"></a>

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                @foreach ($menuItems as $menuItem => $menuItemPath)
                    <li 
                        @if (is_array($menuItemPath)) 
                        class="dropdown"><a>{{ $menuItem }}<i class="bi bi-chevron-down"></i></a>
                            <ul>
                                @foreach ($menuItemPath as $menuItemDrop => $menuItemPathDrop)
                                    <li><a href="{{ $menuItemPathDrop }}">{{ $menuItemDrop }}</a></li>
                                @endforeach
                            </ul>

                        @else
                        ><a class="nav-link @if (url()->current() == $menuItemPath) active @endif"
                            href="{{ $menuItemPath }}">{{ $menuItem }}
                        </a>
                        @endif
                    </li>
                @endforeach
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->
        

    </div>
</header><!-- End Header -->
