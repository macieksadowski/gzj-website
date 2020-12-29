<?php

	/**
	 * This file contains PHP/HTML code which add navbar to every page of the website. It should be added to every page in top of body section.
	 */

	//Add variables with links to subpages and social media links
	require_once "links.php";

?>



<header>
	<!-- This is a container for navbar-->
	<div class="header">

		<!-- Site logo-->
		<a href="<?=$menu['Start'];?>">
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
		<?php
			$i=1;
			
			foreach($menu as $name=>$link)
			{
				if($name == $pageTitle)
				echo'<li class="current" itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"> <span itemprop="name">'.$name.'</span> <meta itemprop="position" content="'.$i.'" /></li>';
				else echo'<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="'.$link.'"> <span itemprop="name">'.$name.'</span></a> <meta itemprop="position" content="'.$i.'" /></li>';
				$i++;
			}
		?>
		</ol>
		
		<!-- Navigation list (social media)-->
		<div class="socials-header">
				<a href="<?=$fb;?>" target="_blank" class="fb">
					<i class="icon-facebook-official"></i>
				</a>
				<a href="<?=$ig;?>" target="_blank" class="ig">
					<i class="icon-instagram"></i>
				</a>
				<a href="<?=$yt;?>" target="_blank" class="yt">
					<i class="icon-youtube-play"></i>
				</a>
				<a href="<?=$sp;?>" target="_blank" class="sp">
					<i class="icon-spotify"></i>
				</a>
				<a href="<?=$sc;?>" target="_blank" class="sc">
					<i class="icon-soundcloud"></i>
				</a>
		</div>
		
		<!-- Contact info in navbar-->
		<div id="contact">
			<a href="tel:<?=$phone;?>">
				<i class="icon-phone"></i> 602 538 140 
			</a>
			<a href="mailto:<?=$mail;?>">
				<i class="icon-at"></i><?=$mail;?>
			</a>
		</div>	
	</div>
</header>
	
