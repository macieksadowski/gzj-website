<?php

/**
 * This file contains PHP/HTML code which add navbar and main div opening tags to every page of the website. It should be added to every page in top of body section.
 */

//Add application specific variables
require_once "../appvars.php";

?>

<!-- This div is used as container for whole page-->
<div class="page-container">

	<header>
	<!-- This is a container for navbar-->
	<div class="header">

		<a href="#"><div class="logo">
			<img src="/img/logo-square.png">
		</div></a>
		
		
		<input id="menu-toggle" type="checkbox" />
		<label class='menu-button-container' for="menu-toggle">
			<div class='menu-button'></div>
		</label>
		
		
		<ol class="menu" itemscope itemtype="https://schema.org/BreadcrumbList">
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="<?=$MERCH;?>"><span itemprop="name">Gadżety</span></a> <meta itemprop="position" content="1" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="<?=$ZAIKS;?>"><span itemprop="name">Generator ZAiKS</span></a> <meta itemprop="position" content="21" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="<?=$CONTRACTS;?>"><span itemprop="name">Generator umów</span></a> <meta itemprop="position" content="3" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="<?=$FINANCES;?>" target="_blank"><span itemprop="name">Finanse</span></a> <meta itemprop="position" content="4" /></li>
			
			<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="../logout.php"><span itemprop="name">Wyloguj się</span></a> <meta itemprop="position" content="5" /></li>
		</ol>
		
		
	</div>
	<div class="title"><h1><?=$PAGE_NAME;?></h1></div>
	</header>
