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
	$errors = $_SESSION['errors'];
	$DBconnection = $_SESSION['DBConnection'];


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
<link href="../modal.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<?php require_once('../menu.php'); 
	?>
	<main>
	<div class="wrapper">	
	<div class="generator" style="flex-direction:column;">
	<div>
		<label><button class="button"><a href="./setSaleSession.php">Nowa sprzedaż</a></button></label>
		<label><button class="button"><a href="./inventory.php">Przegląd zapasów</a></button></label>
	</div>
	<?php
		$query = 'SELECT * FROM sales_session ';
		$list = $DBconnection->getFromDB($query);
		if(is_array($list))
		{
	?>
		<table id="items">
		<tr>
			<th>Id</th>
			<th>Data</th>
			<th>Kwota</th>
			<th> </th>
			<th> </th>
			<th> </th>
		
		</tr>	
		<?php
		
		foreach($list as $key=>$entry) 
		{
				echo '<tr>';
				echo '<td>'.$entry['id'].'</td>';
				echo '<td>'.$entry['date'].'</td>';
				echo '<td>'.$entry['income'].'zł </td>';
				echo '<td><a href="./modifySaleSession.php?id='.$entry['id'].'&mode=view">Przeglądaj</a></td>';
				echo '<td><a href="./modifySaleSession.php?id='.$entry['id'].'&mode=activate">Uaktywnij</a></td>';
				echo '<td><a style="color:red;" href="./modifySaleSession.php?id='.$entry['id'].'&mode=delete">Usuń</a></td>';
				echo '</tr>';
		}

		?>
		</table>
	<?php
		}
		else
		{
	?>
	<p>Brak historii sprzedaży</p>
	<?php		
		}
	?>
</div>


<?php require_once('../../footer.php');
	showModal();	 

	?>

</body>
</html>