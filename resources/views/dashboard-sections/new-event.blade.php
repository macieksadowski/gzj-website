@extends('layouts.dashboard')

@section('inner-content')

<section>
    <div class="dashboard__generator">
        <h2> Wprowadź informacje o nowym wydarzeniu </h2>

        <div class="dashboard__card">
            <div class="dashboard__card-header">
                @yield('card-header')
            </div>
            <div class="dashboard__card-body">
                @yield('card-content')
            </div>
        </div>

    </div>
</section>



@endsection

