@extends('layouts.master')


@section('content')

    @include('layouts.menu.menu')

    @include('sections.hero')

    <main id="main">
        @include('sections.about')
        @include('sections.records')
        @include('sections.events')
        @include('sections.cta')
        @include('sections.team')
        @include('sections.albums')
        @include('sections.merch')
        @include('sections.styles')
    </main><!-- End #main -->

    @include('sections.footer_public')

@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-video@0.8.0/dist/js/splide-extension-video.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('/js/public.js')}}"></script>
@endsection