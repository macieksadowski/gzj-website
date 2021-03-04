<?php
	session_start();
	//Add application specific variables
	require_once "appvars.php";
	//Add application functions
	require_once "functions.php";

	//If user is logged redirect to client panel
	if(@$_SESSION['logged'] == TRUE)
	{
		header('location: '.HOME_SITE);
		exit();
	}
	

	/*VARIABLES
	* **********************************************
	*/
	$_SESSION['logged'] = FALSE;
	if(isset($_SESSION['errors']))
		$errors = $_SESSION['errors'];

	/************************************************/

	
?>
  


<!DOCTYPE HTML>
<html lang="pl">
<head>
	<?php 
		require_once "../head.php"; 
	?>
	<!-- Inform robots to don't index this page-->
	<meta name="robots" content="noindex">
	<!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
	<link href="./style.css" rel="stylesheet" type="text/css" />
	<link href="./modal.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- This div is used as container for whole page-->
<div class="page-container">

	<header>
	<div class="header">
		<a href="/">
			<div class="logo">
				<img src="/img/logo-square.png">
			</div>
		</a>
			
	</div>
	</header>
	
	<main>
	<div class="content">  
		<section>
			<div class="loginform">
				<form action="login.php" method="post">
					<input name="login" type="text" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'">
					<input name="password" type="password"placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'">
					<input name="loginBtn" type="submit"value="Zaloguj się">
				</form>
			</div>
		</section>
	</div>
	</main>
	<?php 
	require_once "../footer.php";
	showModal();	 
	?>
</div>
</body>
</html>

