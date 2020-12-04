<!DOCTYPE HTML>
<html lang="pl">
<head>
	<?php require_once "head.php"; 
	$pageTitle = "Start";
	?>
	<!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
	<link href="/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<!-- This div is used as container for whole page-->
	<div class="page-container">
		<?php require_once "menu.php"; ?>
		<main>
		<!-- This is a container for main content of page. -->
		<div class="content">
			<section>
				<h2>Przed państwem</h2>
				<div id="logoBig">
					<img src="./img/logo-header.gif">
				</div>		
			</section>	
			<section>	
			<div class="socials">
				<h3>Znajdź nas na:</h3>	
				<div id="socials-grid">
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
			</div>
			</section>	
		</div>
		</main>
		<?php require_once "footer.php"; ?>
	</div>
</body>
</html>