<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center justify-content-lg-between">

        <a href="index.html" class="logo me-auto me-lg-0"><img src="{{ asset('/img/logo-header.gif') }}"
                alt="Logo Główny Zawór Jazzu" class="img-fluid"></a>

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                @foreach ($menuItems as $menuItem => $menuItemPath)
                    <li><a class="nav-link 
                  @if (str_contains($menuItemPath,'/' . Request::path())) active @endif
                    "
                            href="{{ $menuItemPath }}">{{ $menuItem }}</a></li>
                @endforeach
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->
        

    </div>
</header><!-- End Header -->
