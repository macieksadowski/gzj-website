<?php

	/**
	* This is a module for users to manage merchandise sales and inventory
	*/
	
	//Add DB credentials and methods
	require_once "../database.php";
	session_start();
	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}
	
	//Add application specific variables
	require_once "../appvars.php";
	//Add application functions
	require_once "../functions.php";
	

	/*VARIABLES
	* **********************************************
	*/


	//Define page name for menu file
	$PAGE_NAME = 'Sprzedaż gadżetów';
	$MERCH  = '';



	/************************************************/




?>




<!DOCTYPE HTML>
<html lang="pl">
<head>
<?php 
	require_once "../../head.php"; 
?>

<meta name="robots" content="noindex">

<link href="../style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<?php require_once('../menu.php'); ?>
	<main>	
	<div class="generator">
	
		<label><div class="button"><a href="./new-sale-session.php">Nowa sprzedaż</a></div></label>
		<label><div class="button"><a href="./inventory.php">Przegląd zapasów</a></div></label>
	
				
	

	</main>
	<footer>
	<div class="footer">
			<a href="#">Główny Zawór Jazzu</a> &copy;&nbsp;2020&nbsp;Maciej&nbsp;Sadowski
	</div>
	</footer>
</div>	
</body>
</html>