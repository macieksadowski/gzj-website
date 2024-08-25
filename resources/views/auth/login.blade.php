@extends('layouts.dashboard')
@section('title', 'Logowanie')

@section('inner-content')

    <section>
        <div class="container-fluid login-container">
            <div class="row justify-content-center">
                <div class="col-md-4 col-sm-8 col-xs-12">
                    <div class="login-form">
                        <h2>Logowanie</h2>
                        <form action="{{ route('login') }}" method="POST">
                            <div class="form-group">
                                @csrf

                                <input name="username" type="text" placeholder="Login" onfocus="this.placeholder=''"
                                    onblur="this.placeholder='Login'" class="form-control">
                                <input name="password" type="password" placeholder="Hasło" onfocus="this.placeholder=''"
                                    onblur="this.placeholder='Hasło'" required autocomplete="current-password"
                                    class="form-control">

                                <div class="form-check">
                                    <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                                    <label for="remember_me" class="form-check-label">Zapamiętaj</label>
                                </div>
                                
                            </div>
                            <input name="loginBtn" type="submit" value="Zaloguj się" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
