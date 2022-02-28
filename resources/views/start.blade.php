@extends('layouts.public')
@section('title', 'Start')

@section('content')

<section>
    <h2>Przed państwem</h2>
    <div id="logoBig">
        <img src={{ asset('img/logo-header.gif') }}>
    </div>
</section>
<section>
    <div class="socials">
        <h3>Znajdź nas na:</h3>
        <div id="socials-grid">
            <a class="social" href={{ $socialLinks['FB'] }} target=" _blank">
                <i class="icon-facebook-official fb"></i>
                <label>Facebook</label>
            </a>
            <a class="social" href={{ $socialLinks['IG'] }} target=" _blank">
                <i class="icon-instagram ig"></i>
                <label>Instagram</label>
            </a>
            <a class="social" href={{ $socialLinks['YT'] }} target=" _blank">
                <i class="icon-youtube-play yt"></i>
                <label>YouTube</label>
            </a>
            <a class="social" href={{ $socialLinks['SP'] }} target=" _blank">
                <i class="icon-spotify sp"></i>
                <label>Spotify</label>
            </a>
            <a class="social" href={{ $socialLinks['SC'] }} target=" _blank">
                <i class="icon-soundcloud sc"></i>
                <label>SoundCloud</label>
            </a>
        </div>
    </div>
</section>

@endsection
