<?php

	/**
	 * This file contains PHP/HTML code which add navbar and main div opening tags to every page of the website. It should be added to every page in top of body section.
	 */

	//Add variables with links to subpages and social media links
	require_once "links.php";

?>

<!-- This div is used as container for whole page-->
<div class="page-container">

	<header>
	<!-- This is a container for navbar-->
	<div class="header">

		<!-- Site logo-->
		<a href="<?=$mainpage;?>">
			<div class="logo">
				<img src="/img/logo-square.png">
			</div>
		</a>
		
		<!-- Menu button for mobile version-->
		<input id="menu-toggle" type="checkbox" />
		<label class='menu-button-container' for="menu-toggle">
				<div class='menu-button'></div>
		</label>
		
		<!-- Navigation list (subpages)-->
		<ol class="menu" itemscope itemtype="https://schema.org/BreadcrumbList">
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="<?=$mainpage;?>"> <span itemprop="name">Start</span></a> <meta itemprop="position" content="1" /></li>
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item"  href="<?=$about;?>"> <span itemprop="name">O zespole</span></a> <meta itemprop="position" content="2" /></li>
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item"  href="<?=$concerts;?>"> <span itemprop="name">Koncerty</span></a> <meta itemprop="position" content="3" /></li>
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item"  href="<?=$records;?>"> <span itemprop="name">Nagrania</span></a> <meta itemprop="position" content="4" /></li>
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item"  href="<?=$offer;?>" target="_blank"> <span itemprop="name">Oferta</span></a> <meta itemprop="position" content="5" /></li>
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item"  href="<?=$rider;?>" target="_blank"> <span itemprop="name">Rider</span></a> <meta itemprop="position" content="6" /></li>
		</ol>
		
		<!-- Navigation list (social media)-->
		<div class="socialsHeader">
				<a href="<?=$fb;?>" target="_blank"> <div class="fb" id="fbHeader">
					<i class="icon-facebook-official"></i>
				</div></a>
				<a href="<?=$ig;?>" target="_blank"><div class="ig" id="igHeader">
					<i class="icon-instagram"></i>
				</div></a>
				<a href="<?=$yt;?>" target="_blank"><div class="yt" id="ytHeader">
					<i class="icon-youtube-play"></i>
				</div></a>
				<a href="<?=$sp;?>" target="_blank"><div class="sp" id="spHeader">
					<i class="icon-spotify"></i>
				</div></a>
				<a href="<?=$sc;?>" target="_blank"><div class="sc" id="scHeader">
					<i class="icon-soundcloud"></i>
				</div></a>
		</div>
		
		<!-- Contact info in navbar-->
		<div id="contact">
			<div><a href="tel:<?=$phone;?>"><i class="icon-phone"></i> 602 538 140 </a></div>
			<div><a href="mailto:<?=$mail;?>"><i class="icon-at"></i><?=$mail;?></a></div>
		</div>
		

		
		
	</div>
	</header>
	
	<main>
	<!-- This is a container for main content of page. ATTENTION: closing tags are included in file footer.php!-->
	<div class="wrapper">
