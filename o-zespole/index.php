<!DOCTYPE HTML>
<html lang="pl">
<head>
	<?php require_once "../head.php";
	//Var used to disable in menu  link to current page 
		$pageTitle = "O zespole";
	?>
	<!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
	<link href="./style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<!-- This div is used as container for whole page-->
	<div class="page-container">
	<?php
	require_once "../menu.php";
	?>
	<main>
	<!-- This is a container for main content of page. -->
	<div class="content">
		<div class="title"><h1><?=$pageTitle;?></h1></div>
		<article>
		<section>
		<div class="about-content">
			<div class="about-photo">
				<img src="../img/about/about (1).jpg">
			</div>
			<div class="about-text">
				<h1>Kim Jesteśmy?</h1>
				<p>
					Główny Zawór Jazzu to&nbsp;energiczny projekt łączący pozornie niedostępne wariacje nurtów jazzowych z&nbsp;wszechobecną muzyką pop. Znane z&nbsp;rozgłośni radiowych polskie przeboje przedstawione&nbsp;są w&nbsp;swingowych aranżacjach, zabierając słuchacza w&nbsp;podróż po&nbsp;zadymionych klubach lat 30&#x2011;tych, 40&#x2011;tych i 50&#x2011;tych.  Dziewięcioosobowy skład z&nbsp;Poznania łamie bariery międzygatunkowe w&nbsp;utworach takich gwiazd&nbsp;jak Monika Brodka, Margaret czy Perfect i&nbsp;wielu innych. Odbiorcy serwowana jest nietuzinkowa mieszanka płynąca pełnią brzmienia wprost przez Główny Zawór Jazzu!
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
				<img src="../img/about/about (2).jpg">
			</div>
			<div class="about-text">
				<h1>Koncerty</h1>
				<p>
				Zespół występuje zarówno w&nbsp;kameralnych klubach, jak i&nbsp;na&nbsp;większych scenach, a&nbsp;także na&nbsp;eventach firmowych. Repertuar jest zawsze dostosowany do&nbsp;charakteru wydarzenia. Spektrum utworów, których retro&#x2011;aranżacje są&nbsp;prezentowane na&nbsp;koncertach, oraz&nbsp;różnorodność gatunków zainteresują zarówno starsze pokolenia, jak&nbsp;i&nbsp;młodzież. Podczas koncertów kreacje rodem z&nbsp;pierwszej połowy zeszłego stulecia ściśle współgrają ze&nbsp;stylistyką utworów, zabierając słuchacza w&nbsp;niepowtarzalną podróż w&nbsp;czasie.
				</p>
			</div>	
		</div>
		</section>
		
		<section>
		<div class="about-content">
			<div class="about-photo">
				<img src="../img/about/about (3).jpg">
			</div>
			<div class="about-text">
				<h1>Live Session</h1>
				<p>
				Zespół w ramach rozwoju swojej twórczości regularnie organizuje nagrania audio i&nbsp;wideo w&nbsp;oryginalnych przestrzeniach w&nbsp;Polsce. Odkrywanie miejsc z&nbsp;duszą to&nbsp;jeden z&nbsp;aspektów, którym realizuje swoją pasję do&nbsp;klimatu retro. Na&nbsp;czas nagrań miejsce to&nbsp;zamienia&nbsp;się w&nbsp;plan filmowy, gdzie wraz z&nbsp;mobilnym studiem nagraniowym tworzone są klimatyczne odsłony kolejnych utworów. Miejsca w&nbsp;których zrealizowano live sessions to&nbsp;m.in. Restauracja Pretekst czy klub DASH w&nbsp;Poznaniu.
				</p>
			</div>	
		</div>
		</section>
		
		<section>
		<div class="about-content">
			<div class="about-photo">
				<img src="../img/about/about (4).jpg">
			</div>
			<div class="about-text" id="moreinfo">
				<h1>Więcej informacji</h1>
				<p>
				Jesteśmy w&nbsp;mediach społecznościowych i&nbsp;serwisach streamingowych. Znajdź&nbsp;nas&nbsp;na: 
				</p>
				<div class="about-socials">
					<a class="social" href="<?=$fb;?>" target=" _blank">	
						<i class="icon-facebook-official fb"></i>
						<label>Facebook</label>						
					</a>
					<a  class="social"href="<?=$ig;?>" target=" _blank">						
						<i class="icon-instagram ig"></i>
						<label>Instagram</label>						
					</a>
					<a  class="social"href="<?=$yt;?>" target=" _blank">						
						<i class="icon-youtube-play yt"></i>
						<label>YouTube</label>						
					</a>
					<a  class="social"href="<?=$sp;?>" target=" _blank">						
						<i class="icon-spotify sp"></i>
						<label>Spotify</label>						
					</a>
					<a  class="social"href="<?=$sc;?>" target=" _blank">						
						<i class="icon-soundcloud sc"></i>
						<label>SoundCloud</label>						
					</a>
				</div>
				<h1>Zapraszamy do&nbsp;kontaktu</h1>
				<a href="tel:<?=$phone;?>" id="about-contact">
					<img src="../img/ico-phone.png">
					<span>
						Bartosz Matuszczak<br />
						602 538 140 
					</span>
				</a>
				<a href="mailto:<?=$mail;?>" id="about-contact">
					<img src="../img/ico-mail.png">
					<span style="margin-top:10px;">
						<?=$mail;?>
					</span>
				</a>
				<button class="button">
					<a href="<?=$menu['Oferta'];?>">
						Oferta koncertowa
					</a>
				</button>
			</div>	
		</div>
		</section>
		</article>
	</div>
	</main>
	<?php require_once "../footer.php";	?>
	</div>
</body>
</html>