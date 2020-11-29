<?php

	/**
	* This is a module for users to to sell (update the number of items in the store)
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
	
	//Preprare SQL query and get products list from DB
	$query = "SELECT * FROM inventory";
	$products = getFromDB($query);
	
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
	<div class="generator sprzedaz">
	
		<div class="formtitle">
		<h1>Nowa sprzedaż</h1>
		<label><div class="button"><a href="./">Wróć</a></div></label>
		</div>
		<form id="new-sale" method="post" >
		<p>Wybierz produkt:
		
		<select onchange="this.form.submit()" name="product">
		<?php	
			//Generate dropdown list 
			foreach($products as $key=>$entry) 
			{
				echo '<option ';
				//Select first position if nothing selected
				if($key == 0 && !isset($_POST['product'])) 
				{
					echo 'selected ';
					$_POST['product'] = 1;
				}
				else if(isset($_POST['product']) && $key+1 == $_POST['product'])  echo 'selected ';
				echo 'value="'.$entry['id'].'">'.$entry['product_name'].'</option>';
			}
		?>
		
		</select>
		<?php
		
		 
		echo '<select name="size" ';
			
				
		//Preprare SQL query and get size list for selected product
		$query = 'SELECT inventory_sizes.size,
			inventory_sizes.in_store,
			inventory_sizes.in_warehouse,
			inventory.id 
			FROM inventory 
			INNER JOIN inventory_sizes 
			ON( inventory.id = inventory_sizes.item_id ) 
			WHERE inventory_sizes.item_id = ';
		//Select first position if nothing selected
		if(!isset($_POST['product']))
			$query .= '1';
		else
			$query.=$_POST['product'];
			//Get data from DB
		$sizes = getFromDB($query);
		//If only one size - disable input
		if(count($sizes) < 2)  echo 'disabled >';
		else echo '>';
		//Add options to dropdown  list
		foreach($sizes as $key=>$entry) 
		{
			echo '<option ';
			//if($key == 0 && !isset($_POST['product'])) echo 'selected ';
			echo 'value="'.$entry['size'].'">';
			if(count($sizes) < 2)  echo $entry['in_store'].'szt.';
			else echo $entry['size'].' ('.$entry['in_store'].'szt.)';
			echo '</option>';
		}
			
			
		?>
		</select>
		
		<input name="amount" type="number" min="1" max="<?=$sizes[$_POST['product']]['in_store'];?>" size="2" placeholder="Ilość" >
		<button  formaction="./update.php" class="button" name="sell"> Sprzedaj</button>
		</p>
		</form>
		
		<table id="items">
		<tr>
			<th>Nazwa</th>
			<th>Cena</th>
			<th>S</th>
			<th>M</th>
			<th>L</th>
			<th>XL</th>
			
		<?php
		$query = 'SELECT * FROM inventory 
			INNER JOIN inventory_sizes 
			ON( inventory.id = inventory_sizes.item_id )';
		$list = getFromDB($query);
		$prev_id = 0;
		foreach($list as $key=>$entry) 
		{
			$id = $entry['id'];
			if($prev_id != $id)
			{
				echo '</tr>';
				echo '<tr>';
				echo '<td>'.$entry['product_name'].'</td>';
				echo '<td>'.$entry['price'].' zł</td>';
			}
			
			echo '<td>'.$entry['in_store'].' ('.$entry['in_warehouse'].') </td>';
			
			$prev_id = $id;
		}	

		?>
		</tr>
		</table>
	
	<?php require_once('../../footer.php'); ?>
<?php
	require_once "script.js";

?>
</body>
</html>