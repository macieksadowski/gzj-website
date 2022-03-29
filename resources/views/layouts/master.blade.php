
<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.header')
    <body>
        <!-- This div is used as container for whole page-->
        <div class="page-container">

            @include('layouts.menu.menu')

            <main>
                <!-- This is a container for main content of page. -->
                <div class="content">
                        @yield('content')
                </div>
            </main>


                <div class="bottom-modal"
                @if (session('success'))
                id="success"
                @else
                id="error"
                @endif
                @if ($errors->any() || session('success'))
                style="display:block;"  >
                @else
                style="display:none;"  >
                @endif
                    <!-- Modal content -->
                    <div class="bottom-modal-content">
                        <div class="bottom-modal-body">
                            <span class="close">&times;</span>

                            <p id="bottom-modal-text">
                                @if (session('success'))
                                {{session('success')}}</ br>
                                @else
                                    @foreach ($errors->all() as $error)
                                    {{ $error }}</ br>
                                    @endforeach
                                @endif

                            </p>
                        </div>
                    </div>

                </div>





            @include('layouts.footer')
        </div>
        @section('scripts')
            <script src="{{ asset('script/modal.js')}}"></script>
        @show

    </body>
</html>
