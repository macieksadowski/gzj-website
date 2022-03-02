@extends('layouts.public')
@section('title', 'Nagrania')

@section('content')

@foreach ($records as $record)

    @if ($record['name'] == 'Koncerty')
        <section>
            <div class="records-stripe ">
                <div class="records-name">
                    <h1>{{$record['name']}}</h1>
                </div>
                <div class="splide records-list">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($record->links as $link)
                            <li class="splide__slide">

                                    <div class="youtube-player" data-id="{{$link['url']}}"></div>

                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="records-content">
                <div class="records-description">
                    <div class="records-name">
                        <h3 style="margin-block-end:0">
                            {{$record['year']}}
                        </h3>
                        <h1 style="margin-block-start:0">
                            {{$record['name']}}
                        </h1>
                    </div>
                    <div class="records-cover">
                        <img src={{asset('img/records/'.$record['cover'])}}>
                    </div>
                </div>
                <div class="records-list">
                    @foreach ($record->links as $link)
                        <div class="records-list-item">
                            <div class="youtube-player" data-id="{{$link['url']}}"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endforeach

@endsection


@section('scripts')
    @parent

    <script src="{{ asset('script/yt.js')}}"></script>
    <script>
        var splide = new Splide( '.splide', {
        type   : 'loop',
        perPage: 3,
        focus  : 'center',
        cover      : true,
	    heightRatio: 0.5,
        } );

        splide.mount();
    </script>

@endsection
