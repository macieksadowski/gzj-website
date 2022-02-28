@extends('layouts.master')
@section('title', 'Logowanie')

@section('content')

<section>
    <div class="loginform">

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <input name="username" type="text" placeholder="Login" onfocus="this.placeholder=''"
                onblur="this.placeholder='Login'" >
            <input name="password" type="password" placeholder="Hasło" onfocus="this.placeholder=''"
                onblur="this.placeholder='Hasło'" required autocomplete="current-password">

            <label for="remember_me">
                <input id="remember_me" type="checkbox"  name="remember">
                <span>Zapamiętaj</span>
            </label>

            <input name="loginBtn" type="submit" value="Zaloguj się">
        </form>
    </div>
</section>

@endsection
