<?php
	/**
    * This is a module for users to add to store items from warehouse, or add items to warehouse
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
	$errors = @$_SESSION['errors'];
	$DBconnection = $_SESSION['DBConnection'];

	//Define page name for menu file
	$PAGE_NAME = 'Sprzedaż gadżetów';
	$MERCH  = '';
	
	//Preprare SQL query and get products list from DB
	$query = "SELECT
		inventory_products.product_name,
		inventory.size,
		inventory.size_id,
		inventory_products.price,
		inventory.in_store,
		inventory.in_warehouse
		FROM
		inventory
		INNER JOIN inventory_products ON(
			inventory.product_id = inventory_products.id
		)";
	$products = $DBconnection->getFromDB($query);
	
	if(isset($_POST['products']))
	{
		foreach($products as $key=> $item)
		{
			$oldVals[$item['size_id']]['price'] = $item['price'];
			$oldVals[$item['size_id']]['in_store'] = $item['in_store'];
			$oldVals[$item['size_id']]['in_warehouse'] = $item['in_warehouse'];
		}
		$toUpdate = $_POST['products'];
		ksort($toUpdate);
		ksort($oldVals);
		foreach($toUpdate as $key=>$item)
		{
		 $isDiff = array_diff($item,$oldVals[$key]);
		 if(!empty($isDiff))
		 {
			$query = 'UPDATE inventory 
				SET in_store='.$item['in_store'].
				',in_warehouse='.$item['in_warehouse'].
				' WHERE size_id = '.$key;
			$result = $DBconnection->sendToDB($query);
			if($result == 0)
			{
				$query = 'UPDATE inventory
					INNER JOIN inventory_products 
					ON (inventory.product_id = inventory_products.id) 
					SET inventory_products.price = '.$item['price'].
					',inventory.in_store = '.$item['in_store'].
					',inventory.in_warehouse = '.$item['in_warehouse'].
					' WHERE inventory.size_id = '.$key;
				$result = $DBconnection->sendToDB($query);
				if($result == 0)
				{
					$_SESSION['success'] = TRUE;
					resetAllErrorFlags();
				}
			}
			if($result != 0)
			{
				unset($_SESSION['success']);
				$_SESSION['errors'][$result] = TRUE;
			}
		
			
		 }
		}
		
		foreach($toUpdate as $item)
		{

		}
	}

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

	<?php require_once('../menu.php'); ?>
	<main>	
	<div class="generator">
	
		<div class="formtitle">
		<h1>Zarządzaj zapasami</h1>
	
		<div>
			<p>
				Tutaj możesz zmienić ilość towarów w sklepie, zaktualizować ich ceny, a także zarządzać stanem magazynowym.
				<br />Pamiętaj aby <b>zapisać zmiany</b> po zakończeniu edycji!
			</p>
			<label><button class="button"><a href="#TODO">Dodaj nowy produkt</a></button></label>
			<button class="button" type="submit" form="manageWarehouse">Zapisz</button>
			<label><button class="button"><a href="#TODO">Zapisz</a></button></label>
			
			
		</div>
		</div>
		<div class="tableContainer">
		<form id="manageWarehouse" method="post" >
			<table id="items">
				<tr>
					<th>Produkt</th>
					<th>Rozmiar</th>
					<th>Cena</th>
					<th>W sklepie</th>
					<th>Magazyn</th>
					
				</tr>
				<?php
				foreach($products as $key=> $item)
				{
				?>
					<tr>
						<td>
							<?=$item['product_name'];?>
						</td>
						<td>
							<?=$item['size'];?>
						</td>
						<td>
							<input type="number" class="price" name="products[<?=$item['size_id'];?>][price]" min="0" max="99" step="1" value="<?=$item['price'];?>">
							<span class="currency">
								zł
							</span>
						</td>
						<td class="nowrapCell">
						<div class="plusMinusInput">
							
							<input readonly type="text" name="products[<?=$item['size_id'];?>][in_store]"  value="<?=$item['in_store'];?>">
							<span class="minus 
							<?php
								if($item['in_store'] < 1)
								echo ' disabled';
							?>
								" onclick="changeVal(this)">
								-
							</span>
							<span class="plus
							<?php
								if($item['in_warehouse'] < 1)
								echo ' disabled';
							?>
								" onclick="changeVal(this)">
								+
							</span>
						</div>
						</td>
						<td>
							<input class="inWarehouse" onchange="disablePlusMinus(this)" type="number" name="products[<?=$item['size_id'];?>][in_warehouse]" min="0" max="999" value="<?=$item['in_warehouse'];?>">
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		</form>
		</div>
	</div>
	<?php require_once('../../footer.php');
	showModal();	
	 ?>
	<script src="script.js"></script>
	
</body>
</html>