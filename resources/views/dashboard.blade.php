@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')

<section>
    <div class="generator">
        <p>Jesteś zalogowany jako <b>{{ Auth::user()->username }}</b>. Wybierz moduł z menu u góry strony.</p>
    </div>

</section>

@endsection


