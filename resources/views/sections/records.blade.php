<!-- ======= Records Section ======= -->
<section id="records" class="splide">
    <div class="container section-title">
      <h2>Nagrania</h2>
      <p>Zobacz nasze nagrania</p>
    </div>
    <div class="splide__track">
      <ul class="splide__list">
        @isset($records)
           @foreach ($records as $key => $record)
           <li class="splide__slide" data-splide-youtube="https://www.youtube.com/watch?v={{$record['url']}}">
              <img src="https://i.ytimg.com/vi/{{$record['url']}}/hqdefault.jpg">
            </li>
           @endforeach
        @endisset
      </ul>
    </div>
  </section><!-- End Records Section -->