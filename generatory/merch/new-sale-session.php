<?php

	/**
	* This is a module for users to to sell (update the number of items in the store)
	*/
	session_start();
	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}
	
	//Add database access credentials
	require_once "../connectvars-local.php";
	//Add application specific variables
	require_once "../appvars.php";
	//Add application functions
	require_once "../functions.php";
	
	//Define page name for menu file
	$PAGE_NAME = 'Sprzedaż gadżetów';
	$MERCH  = '';
	
	//Preprare SQL query and get products list from DB
	$query = "SELECT * FROM inventory";
	$products = getFromDB($query);
	


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
		<form id="new-sale" method="post">
		<p>Wybierz produkt:
		
		<select onchange="this.form.submit()" name="product">
		<?php	
			
			foreach($products as $key=>$entry) 
			{
				echo '<option ';
				if($key == 0 && !isset($_POST['product'])) echo 'selected ';
				else if(isset($_POST['product']) && $key+1 == $_POST['product'])  echo 'selected ';
				echo 'value="'.$entry['id'].'">'.$entry['product_name'].'</option>';
			}
		?>
		
		</select>
		<?php
		
		 
		echo '<select name="size" ';
		
			if(isset($_POST['product']))
			{
				
				
				//Preprare SQL query and get size list for selected product
				$query = 'SELECT inventory_sizes.size,
				  inventory_sizes.in_store,
				  inventory.id 
				  FROM inventory 
				  INNER JOIN inventory_sizes 
				  ON( inventory.id = inventory_sizes.item_id ) 
				  WHERE inventory_sizes.item_id = '.$_POST['product'];
				$sizes = getFromDB($query);
				if(count($sizes) < 2)  echo 'disabled >';
				else echo '>';
				
				foreach($sizes as $key=>$entry) 
				{
					echo '<option value="'.$entry['id'].'">';
					if(count($sizes) < 2)  echo $entry['in_store'].'szt.';
					else echo $entry['size'].' ('.$entry['in_store'].'szt.)';
					echo '</option>';
				}
			}
			else echo 'disabled >';
			
		?>
		</select>
		
		<input name="amount" type="number" min="1" size="2" placeholder="Ilość" >
		<button  class="button" name="sell"> Sprzedaj</button>
		</p>
		</form>	

		
	
	<?php require_once('../../footer.php'); ?>
<?php
	require_once "script.js";

?>
</body>
</html>