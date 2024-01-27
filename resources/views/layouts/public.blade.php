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
    <script src="{{ asset('/js/public.min.js')}}"></script>
@endsection