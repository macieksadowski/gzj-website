<!DOCTYPE HTML>
<html lang="pl">
<head>
	<?php 
		require_once "../head.php"; 
		require_once "./yt-links.php";
		$pageTitle = "Nagrania";
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
		<section>
		<div class="records-stripe">
			<div class="records-name">
				<h1>Koncerty</h1>
			</div>
			<div class="records-list">
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$live1iframe;?>"></div>
				</div>
				<div class="records-list-item" >
					<div class="youtube-player" data-id="<?=$live2iframe;?>"></div>
				</div>	
			</div>
		</div>	
		</section>

		<section>
		<div class="records-content">
			<div class="records-description">
				<div class="records-name">
					<h3 style="margin-block-end:0">
						2020
					</h3>
					<h1 style="margin-block-start:0">
						Pałac Jaśminowy
					</h1>
				</div>
				<div class="records-cover">
					<img src="<?=$jasminCover?>">
				</div>	
			</div>
			<div class="records-list">
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$jasmin1iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$jasmin2iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$jasmin3iframe;?>"></div>
				</div>
			</div>	
		</div>
		</section>

		<section>
		<div class="records-content">
			<div class="records-description">
				<div class="records-name">
					<h3 style="margin-block-end:0">
						2019
					</h3>
					<h1 style="margin-block-start:0">
						Orle Gniazdo
					</h1>
				</div>
				<div class="records-cover">
					<img src="<?=$orleCover?>">
				</div>	
			</div>
			<div class="records-list">
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$orle1iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$orle2iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$orle3iframe;?>"></div>
				</div>
			</div>	
		</div>
		</section>

		<section>
		<div class="records-content">
			<div class="records-description">
				<div class="records-name">
					<h3 style="margin-block-end:0">
						2019
					</h3>
					<h1 style="margin-block-start:0">
						DASH
					</h1>
				</div>
				<div class="records-cover">
					<img src="<?=$dashCover?>">
				</div>
			</div>
			<div class="records-list">
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$dash1iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$dash2iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$dash3iframe;?>"></div>
				</div>
			</div>	
		</div>
		</section>

		<section>
		<div class="records-content">
			<div class="records-description">	
				<div class="records-name">
					<h3 style="margin-block-end:0">
						2018
					</h3>
					<h1 style="margin-block-start:0">
						Pretekst
					</h1>
				</div>
				<div class="records-cover">
					<img src="<?=$pretCover?>">
				</div>
			</div>
			<div class="records-list">
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$pret1iframe;?>"></div>
				</div>
				<div class="records-list-item">
					<div class="youtube-player" data-id="<?=$pret2iframe;?>"></div>
				</div>
			</div>	
		</div>
		</section>
	</div>
	</main>
	<?php require_once "../footer.php";	?>
</div>
<script src="../script/yt.js" type="text/javascript"></script>
</body>
</html>