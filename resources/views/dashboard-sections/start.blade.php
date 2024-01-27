@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('inner-content')

<section>
    <div class="generator">
        <p>Jesteś zalogowany jako <b>{{ Auth::user()->username }}</b>. Wybierz moduł z menu u góry strony.</p>
    </div>

</section>

@endsection


