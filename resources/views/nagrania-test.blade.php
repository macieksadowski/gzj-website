<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="google-site-verification" content="MiQKEZcz4YKn7I5v_HHhbPyBwIhTkUQ3A0maBFTjZN0" />
        <meta name="google-site-verification" content="C0IRoVkYt0yqUZta7TPVZWL95zO-aYn-CD9XhvhikEI" />

        <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
        @yield('description')
        @yield('keywords')

        <!-- 	Facebook meta  	-->
        <meta property="og:title" content="Główny Zawór Jazzu" />
        <meta property="og:image:type" content="image/jpg" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:url" content="glownyzaworjazzu.pl/" />
        <meta property="og:image" content="http://glownyzaworjazzu.pl/assets/img/fb.jpg" />
        <meta property="og:image:secure_url" content="https://glownyzaworjazzu.pl/assets/img/fb.jpg" />




        <!-- 	Google funcs	-->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "http://www.glownyzaworjazzu.pl",
            "logo": "http://www.glownyzaworjazzu.pl/assets/img/android-chrome-512x512.png"
        }
        </script>



        <link rel="shortcut icon" sizes="32x32" href={{ asset('img/favicon-32x32.png') }}>
        <link rel="icon" sizes="192x192" href={{ asset('/img/android-chrome-192x192.png')}}>
        <link rel="apple-touch-icon" sizes="180x180" href={{ asset('img/apple-touch-icon.png')}}>
        <link rel="icon" type="image/png" sizes="32x32" href={{ asset('img/favicon-32x32.png')}}>
        <link rel="manifest" href={{ asset('img/site.webmanifest')}}>
        <link rel="mask-icon" href={{ asset('img/safari-pinned-tab.svg')}} color="#292929">
        <meta name="msapplication-TileColor" content="#292929">
        <meta name="theme-color" content="#292929">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <link
            href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cinzel:wght@400;700&family=Petit+Formal+Script&family=Raleway:wght@300;400;600&display=swap"
            rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/js/splide.min.js"></script>


        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.4/b-2.2.2/kt-2.6.4/r-2.2.9/datatables.min.js"></script>

        <!--<link href={{ asset('/css/pageCSS/'.$css) }} rel="stylesheet" type="text/css">-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/css/splide.min.css">

    </head>
<body>
@foreach ($records as $record)

    @if ($record['name'] == 'Koncerty')
        <section>
            <div class="records-stripe">
                <div class="records-name">
                    <h1>{{$record['name']}}</h1>
                </div>
                <div class="splide">
                    <div class="splide__track">
                          <ul class="splide__list">
                            @foreach ($record->links as $link)
                              <li class="splide__slide">
                                <div class="records-list-item ">
                                    <div class="youtube-player" data-id="{{$link['url']}}"></div>
                                </div>
                              </li>

                              @endforeach
                          </ul>
                    </div>
                  </div>



            </div>
        </section>

    @endif
@endforeach
    </body>
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
