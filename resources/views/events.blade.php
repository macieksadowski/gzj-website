@extends('layouts.public')
@section('title', 'Koncerty')



@section('content')

<div class="event-grid">
    @isset($events)
        @foreach ($events as $key => $event)
        <div class="event">

            <div class="event-cover">
                <img src="{{$event['cover']['source']}}" loading="lazy">
            </div>
            <div class="event-text">
                <div class="event-name">
                    <h1>
                        {{$event['name']}}
                    </h1>
                </div>
                <div class="event-date">
                    {{strftime('%A %e.%m.%yr. godz.%H:%M', $event['start_time']->getTimestamp())}}
                </div>
                @if ($event['is_online'])
                <div class="ticketInfo">Wydarzenie online</div>

                @else
                    @isset($event['ticket_uri'])
                        <button><a href={{$event['ticket_uri']}} target="_blank">Kup Bilet</a></button>
                    @endisset
                    @empty($event['ticket_uri'])
                        @php
                            $ticketInfo = '';
                            $re = '/wstęp wolny/miu';
                            preg_match_all($re, $event['description'], $matches, PREG_SET_ORDER, 0);
                            if (!empty($matches)) {
                                $ticketInfo = 'Wstęp wolny';
                            } else {
                                $ticketInfo = 'Bilety do nabycia u&nbsp;organizatora';
                            }
                        @endphp
                        <div class="ticketInfo">{{$ticketInfo}}</div>
                    @endempty
                @endif


                <a href="https://facebook.com/events/{{$event['id']}}" target="_blank"
                    class="event-social fb">
                    <i class="icon-facebook-official"></i>
                </a>
                <div style="clear:both"></div>
                <div class="event-place">
                    <b>
                        {{$event['place']['name']}}
                    </b><br />
                    @isset($event['place']['location'])
                        {{$event['place']['location']['city']}}, {{$event['place']['location']['street']}}
                    @endisset
                </div>
            </div>
        </div>
        @endforeach
    @endisset

    @empty($events)

    <div class="title">
        <h1>BRAK NADCHODZĄCYCH KONCERTÓW</h1>
    </div>

    @endempty
</div>

@endsection

