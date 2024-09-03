<!-- ======= Header ======= -->
<header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center justify-content-lg-between">

      <div class="logo me-auto me-lg-0"><img src="{{asset('/img/logo-header.gif')}}" alt="Logo Główny Zawór Jazzu" class="img-fluid"></div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto" href="{{ $menuItems['O zespole']}}">O zespole</a></li>
          <li><a class="nav-link scrollto " href="{{ $menuItems['Nagrania']}}">Nagrania</a></li>
          <li><a class="nav-link scrollto" href="{{ $menuItems['Koncerty']}}">Koncerty</a></li>
          <li><a class="nav-link scrollto " href="{{ $menuItems['Dyskografia']}}">Dyskografia</a></li>
          <li class="dropdown"><span>Do pobrania</span> <i class="bi bi-chevron-down"></i>
            <ul>
              <!--
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>-->
              <li><a href="{{ $menuItems['Oferta'] }}" target="blank">Oferta</a></li>
              <li><a href="{{ $menuItems['Do pobrania']['Presspack'] }}" target="blank">Presspack</a></li>
              <li><a href="{{ $menuItems['Do pobrania']['Rider'] }}" target="blank">Rider</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="{{ $menuItems['Kontakt']}}" class="nav-link"><span>Kontakt</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="tel:{{ $phone }}" class="nav-link"><i class="bi-telephone" ></i>{{ $phone }}</a></a></li>
              <li><a href="mailto:{{ $mail }}" class="nav-link"><i class="bi-at" ></i>{{ $mail }}</a></a></li>
            </ul>
          </li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->