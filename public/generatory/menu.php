<?php

/**
 * This file contains PHP/HTML code which add navbar to every page of the website. It should be added to every page in top of body section.
 */

//Add application specific variables
require_once "../appvars.php";
//Add variables with links to subpages and social media links
require_once "links.php";

?>

<header>
<!-- This is a container for navbar-->
<div class="header">

	<div class="logo">
		<img src="/assets/img/logo-square.png">
	</div>
	
	
	<input id="menu-toggle" type="checkbox" />
	<label class='menu-button-container' for="menu-toggle">
		<div class='menu-button'></div>
	</label>
	
	
	<ol class="menu" itemscope itemtype="https://schema.org/BreadcrumbList">
	<?php
		$i=1;
			
		foreach($menu as $name=>$link)
		{
			if($name == $PAGE_NAME)
			echo'<li class="current" itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"> <span itemprop="name">'.$name.'</span> <meta itemprop="position" content="'.$i.'" /></li>';
			else echo'<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="'.$link.'"> <span itemprop="name">'.$name.'</span></a> <meta itemprop="position" content="'.$i.'" /></li>';
			$i++;
		}
	?>
	
		
		
		
	</ol>
	
	
</div>
<div class="title"><h1><?=$PAGE_NAME;?></h1></div>
</header>
