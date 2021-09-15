<?php
require_once '../../page.php';

$page = new page();
$page->setLayout(SUBPAGELAYOUT);
$socialLinks = $page->menu->getLinks();
$pageInfo = $page->getPageInfo();

ob_start();

?>

<article>
    <section>
        <div class="about-content">
            <div class="about-photo">
                <img src="../assets/img/about/about (1).jpg">
            </div>
            <div class="about-text">
                <h1>Kim Jesteśmy?</h1>
                <p>
                    Główny Zawór Jazzu to&nbsp;energiczny projekt łączący pozornie niedostępne wariacje nurtów
                    jazzowych z&nbsp;wszechobecną muzyką pop. Znane z&nbsp;rozgłośni radiowych polskie przeboje
                    przedstawione&nbsp;są w&nbsp;swingowych aranżacjach, zabierając słuchacza w&nbsp;podróż
                    po&nbsp;zadymionych klubach lat 30&#x2011;tych, 40&#x2011;tych i 50&#x2011;tych.
                    Dziewięcioosobowy skład z&nbsp;Poznania łamie bariery międzygatunkowe w&nbsp;utworach takich
                    gwiazd&nbsp;jak Monika Brodka, Margaret czy Perfect i&nbsp;wielu innych. Odbiorcy serwowana jest
                    nietuzinkowa mieszanka płynąca pełnią brzmienia wprost przez Główny Zawór Jazzu!
                </p>
            </div>
        </div>
    </section>

    <section>
        <div class="about-stripe">
            <div id="numFactsStripe">
                <div class="num-fact">
                    <div class="num-fact-number">3</div>
                    <div class="num-fact-text">wokalistki</div>
                </div>
                <div class="num-fact">
                    <div class="num-fact-number">6</div>
                    <div class="num-fact-text">instrumentalistów</div>
                </div>
                <div class="num-fact">
                    <div class="num-fact-number">9</div>
                    <div class="num-fact-text">osobowy skład</div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="about-content">
            <div class="about-photo">
                <img src="../assets/img/about/about (2).jpg">
            </div>
            <div class="about-text">
                <h1>Koncerty</h1>
                <p>
                    Zespół występuje zarówno w&nbsp;kameralnych klubach, jak i&nbsp;na&nbsp;większych scenach,
                    a&nbsp;także na&nbsp;eventach firmowych. Repertuar jest zawsze dostosowany do&nbsp;charakteru
                    wydarzenia. Spektrum utworów, których retro&#x2011;aranżacje są&nbsp;prezentowane
                    na&nbsp;koncertach, oraz&nbsp;różnorodność gatunków zainteresują zarówno starsze pokolenia,
                    jak&nbsp;i&nbsp;młodzież. Podczas koncertów kreacje rodem z&nbsp;pierwszej połowy zeszłego
                    stulecia ściśle współgrają ze&nbsp;stylistyką utworów, zabierając słuchacza
                    w&nbsp;niepowtarzalną podróż w&nbsp;czasie.
                </p>
            </div>
        </div>
    </section>

    <section>
        <div class="about-content">
            <div class="about-photo">
                <img src="../assets/img/about/about (3).jpg">
            </div>
            <div class="about-text">
                <h1>Live Session</h1>
                <p>
                    Zespół w ramach rozwoju swojej twórczości regularnie organizuje nagrania audio i&nbsp;wideo
                    w&nbsp;oryginalnych przestrzeniach w&nbsp;Polsce. Odkrywanie miejsc z&nbsp;duszą to&nbsp;jeden
                    z&nbsp;aspektów, którym realizuje swoją pasję do&nbsp;klimatu retro. Na&nbsp;czas nagrań miejsce
                    to&nbsp;zamienia&nbsp;się w&nbsp;plan filmowy, gdzie wraz z&nbsp;mobilnym studiem nagraniowym
                    tworzone są klimatyczne odsłony kolejnych utworów. Miejsca w&nbsp;których zrealizowano live
                    sessions to&nbsp;m.in. Restauracja Pretekst, Ośrodek Kultury w Komornikach czy też klub DASH
                    w&nbsp;Poznaniu.
                </p>
            </div>
        </div>
    </section>

    <section>
        <div class="about-content">
            <div class="about-photo">
                <img src="../assets/img/about/about (4).jpg">
            </div>
            <div class="about-text" id="moreinfo">
                <h1>Więcej informacji</h1>
                <p>
                    Jesteśmy w&nbsp;mediach społecznościowych i&nbsp;serwisach streamingowych.
                    Znajdź&nbsp;nas&nbsp;na:
                </p>
                {{SOCIALBAR2}}
                <h1>Zapraszamy do&nbsp;kontaktu</h1>

                <a href="tel:{{PHONE}}" id="about-contact">
                    <img src="../assets/img/ico-phone.png">
                    <span>
                        Bartosz Matuszczak<br />
                        <?php echo $socialLinks['PHONE']; ?>
                    </span>
                </a>
                <a href="mailto:{{MAIL}}" id="about-contact">
                    <img src="../assets/img/ico-mail.png">
                    <span style="margin-top:10px;">
                        <?php echo $socialLinks['MAIL']; ?>
                    </span>
                </a>
                <button class="button">
                    <a href="{{OFERTA}}">
                        Oferta koncertowa
                    </a>
                </button>
            </div>
        </div>
    </section>
</article>

<?php

$str = ob_get_clean();

$socialBar2Template = $page->getTemplate('socialBar2');
$page->fillWithData($socialBar2Template, $page->menu->getLinks());

$page->fillWithData(
    $str,
    [
        'SOCIALBAR2' => $socialBar2Template,
        'OFERTA' => $page->menu->getItems()['Oferta'],
        'MAIL' => $page->menu->getLinks()['MAIL'],
        'PHONE' => $page->menu->getLinks()['PHONE'],
    ]
);

$page->show($str);