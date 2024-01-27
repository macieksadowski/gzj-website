<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.header')
    <body>

        @yield('content')

        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
      
        @section('scripts')
        <!-- Main JS File -->
        <script src="{{ asset('/js/main.min.js')}}"></script>
        @show
        
      
    </body>
      
</html>
