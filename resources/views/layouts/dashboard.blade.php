@extends('layouts.master')


@section('content')

    @include('layouts.menu.menu_dashboard')

    <main id="main" class="dashboard">
        @yield('inner-content')
    </main><!-- End #main -->

    <div class="modal-bottom container-fluid"
        @if (session('success')) id="success"
                @else
                id="error" @endif
        @if ($errors->any() || session('success')) style="display:block;"  >
                @else
                style="display:none;"  > @endif
        <!-- Modal content -->
        <div class="row align-items-center modal-bottom__content">
            <div class="col-11">
                <p id="modal-bottom-text">
                    @if (session('success'))
                        {{ session('success') }}</ br>
                    @else
                        @foreach ($errors->all() as $error)
                            {{ $error }}</ br>
                        @endforeach
                    @endif

                </p>
            </div>
            <div class="col-1">
                <span id="modal-bottom-close" class="modal-bottom__close">&times;</span>
            </div>
            


        </div>

    </div>

    @include('dashboard-sections.footer_dashboard')

@endsection

@section('scripts')
    @parent
    <script src="{{ asset('/js/dual-listbox.js') }}"></script>

    <script src="{{ asset('/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('/js/dataTables.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/js/buttons.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('/js/dataTables.searchPanes.min.js') }}"></script>
    <script src="{{ asset('/js/searchPanes.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('/js/select.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('/js/select2.min.js') }}"></script>
    <script src="{{ asset('/js/select2.pl.js') }}"></script>
    
    <script src="{{ asset('/js/dashboard.js') }}"></script>

    @stack('scripts')
@endsection
