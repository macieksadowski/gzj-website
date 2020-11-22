<!DOCTYPE HTML>
<html lang="pl">

<head>
	<?php require_once "head.php"; ?>
	<!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
	<link href="/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<?php
	require_once "menu.php";
	?>
	
	<div class="content">
	<section>
		<h2>Przed państwem</h2>
		<div id="logoBig">
			<img src="./img/logo-header.gif">
		</div>		
	</section>
	
	<section>	
	<div class="socials">
		<h3>Odwiedź nas tutaj:</h3>
		<a href="<?=$fb;?>" target=" _blank"><div class="socialdiv"><div class="fb">
			<i class="icon-facebook-official"></i>
		</div>Facebook</div></a>
		<a href="<?=$ig;?>" target=" _blank"><div class="socialdiv"><div class="ig">
			<i class="icon-instagram"></i>
		</div>Instagram</div></a>
		<a href="<?=$yt;?>" target=" _blank"><div class="socialdiv">	<div class="yt">
			<i class="icon-youtube-play"></i>
		</div>YouTube</div></a>
		<a href="<?=$sp;?>" target=" _blank"><div class="socialdiv">	<div class="sp">
			<i class="icon-spotify"></i>
		</div>Spotify</div></a>
		<a href="<?=$sc;?>" target=" _blank"><div class="socialdiv">	<div class="sc">
			<i class="icon-soundcloud"></i>
		</div>SoundCloud</div>
		<div style="clear:both"></div></a>
		</div>
	</div>
	<section>	
	
	<?php
	require_once "footer.php";
	?>



</body>
</html>