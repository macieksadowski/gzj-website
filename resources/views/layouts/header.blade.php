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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.4/b-2.2.2/kt-2.6.4/r-2.2.9/datatables.min.js"></script>
<link href={{ asset('/css/dual-listbox.css') }} rel="stylesheet" type="text/css">
<link href={{ asset('/css/main.css') }} rel="stylesheet" type="text/css">
<link href={{ asset('/css/header.css') }} rel="stylesheet" type="text/css">
<link href={{ asset('/css/modal.css') }} rel="stylesheet" type="text/css">
<link href={{ asset('/css/fontello.css') }} rel="stylesheet" type="text/css">
<link href={{ asset('/css/pageCSS/'.$css) }} rel="stylesheet" type="text/css">
<link href={{ asset('/css/table.css') }} rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://cdn.rawgit.com/maykinmedia/dual-listbox/master/dist/dual-listbox.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>-->

