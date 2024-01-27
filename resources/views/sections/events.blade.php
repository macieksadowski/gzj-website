<!-- ======= Events Section ======= -->
<section id="events" class="events">
    <div class="container">

        <div class="section-title">
            <h2>Koncerty</h2>
            <p>Sprawdź gdzie gramy</p>
        </div>
        @if ($errors->any())
          <p class="events__noevents">Aktualne terminy koncertów można znaleźć <a href="{{ $socialLinks['FB'] }}/events" target=" _blank">na naszym Facebooku</a></p>
        @else

        <div class="row">
            @isset($events)
                @foreach ($events as $key => $event)
                    <div class="col-lg-6 col-md-12 d-flex align-items-stretch">
                        <div class="event-box">
                            <div class="cover">
                                <img src="{{ $event['cover']['source'] }}" loading="lazy">
                            </div>
                            <h4>{{ $event['name'] }}</h4>
                            <div class="container event-info">
                                <div class="row">
                                    <div class="col-6 col-sm-3 order-1">
                                        <p>{{ strftime('%A %e.%m.%yr. godz.%H:%M', $event['start_time']->getTimestamp()) }}
                                        </p>
                                    </div>
                                    <div class="col-6 order-2 event-info__link">
                                        @if ($event['is_online'])
                                            <p>Wydarzenie online</p>
                                        @else
                                            @isset($event['ticket_uri'])
                                                <a href="{{ $event['ticket_uri'] }}" target="_blank" class="link-button">Kup
                                                    bilet</a>
                                            @endisset
                                            @empty($event['ticket_uri'])
                                              @php
                                                $ticketInfo = '';
                                                $re = '/wstęp wolny/miu';
                                                preg_match_all($re, $event['description'], $matches, PREG_SET_ORDER, 0);
                                              @endphp
                                              @if (!empty($matches))
                                                  <p>Wstęp wolny</p>
                                              @else
                                                <p>Bilety do nabycia u&nbsp;organizatora</p>
                                              @endif
                                            @endempty
                                          @endif
                                      </div>
                                      <div class="col-3 order-4 order-sm-3 event-info__link ">
                                        <a href="https://facebook.com/events/{{ $event['id'] }}" target="_blank"
                                        class="link-button link-button_social"><i class="bi bi-facebook"></i></a>
                                      </div>
                                  <div class="col-9 col-sm-6 order-3 order-sm-4">
                                    <p>
                                        <strong>{{ $event['place']['name'] }}</strong><br />
                                        @isset($event['place']['location']['city'])
                                            {{ $event['place']['location']['city'] }}
                                        @endisset
                                        @isset($event['place']['location']['street'])
                                            -
                                            {{ $event['place']['location']['street'] }}
                                        @endisset
                                    </p>
                                  </div>
                                  </div>
                            </div>
                          </div>
                    </div>
                  @endforeach
              @endisset
              @empty($events)
                <p class="events__noevents">Chwilowo brak nadchodzących koncertów - obserwuj naszą stronę, aby nie przeoczyć, gdy się pojawią.</p>
              @endempty
          </div>
          @endif
      </div>
</section><!-- End Events Section -->
