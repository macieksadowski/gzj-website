
<?php
	/**
	* This is a module for users to add to store items from warehouse, or add items to warehouse
	* TODO: Delete product from list, add new - prevent adding same size more than once
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
	

	/************************************************/
		
	//If delete button by product pressed then remve this product from database
	if(isset($_POST['productToRemove']))
	{
		$size_id = $_POST['productToRemove'];
		$products = $_POST['products'];
		$occurences = 0;
		$productName = '';
		foreach($products as $key=>$item)
		{
			if(strval('item') == $size_id)
			{
				$occurences++;
				$productName = $item[''];
			}
		}
		if($occurences < 2)
		{

		}
		$query = "DELETE FROM inventory WHERE size_id = $size_id";
		$result = $DBconnection->sendToDB($query);
		if($result == 0)
		{
		   
			resetAllErrorFlags();
			$_SESSION['success'] = 'Usunięto produkt (id:'.$size_id.') !';
		}
		else
		{
			$_SESSION['errors'][$result] = TRUE;
		}
	}
	//If form submitted (Save button pressed) then update database
	else if(isset($_POST['products']))
	{
		$oldVals = $_SESSION['oldVals'];
		$toUpdate = $_POST['products'];
		//Compare old data with submitted data
		ksort($toUpdate);
		ksort($oldVals);
		$price = 0;
		foreach($toUpdate as $key=>$item)
		{
			//If product is same type as previous product then it has same price so it hasn't field 'price' - then copy last available value
			if(isset($item['price']))
			{
				$price = $item['price'];
				
			}
			else
			{
				$item['price'] = $price;
			}
			//Comparision
			$isDiff = array_diff($item,$oldVals[$key]);
			if(!empty($isDiff))
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
					$_SESSION['success'] = 'Zmiany zapisano pomyślnie';
					resetAllErrorFlags();
					if(isset($_POST['saveandclose']))
					{
						header('location: index.php');
						exit();
					}
				}
				else
				{
					unset($_SESSION['success']);
					$_SESSION['errors'][$result] = TRUE;
				}
			}
		}
		if(!isset($result) && isset($_POST['saveandclose']))
		{
			header('location: index.php');
			exit();
		}
		
	}
	//If new product form submitted then add new product to database
	else if(isset($_POST['newProduct']))
	{
		$name = $_POST['newProduct']['name'];
		$price = $_POST['newProduct']['price'];
		$sizes = $_POST['newProduct']['sizes']['size'];
		$amounts = $_POST['newProduct']['sizes']['amount'];
		$query = "INSERT INTO inventory_products VALUES (0,'$name',$price)";
		$result = $DBconnection->sendToDB($query);
		if($result == 0)
		{
			$query = "SELECT id FROM `inventory_products` WHERE `product_name` = '$name'";
			$result = $DBconnection->getFromDB($query);
			$id = $result[0]['id'];
			foreach($sizes as $key => $item)
			{
				$query = 'INSERT INTO inventory (size,product_id,in_warehouse) VALUES ("'.$item.'",'.$id.','.$amounts[$key].')';
				$result = $DBconnection->sendToDB($query);
				if($result != 0)
				{
					unset($_SESSION['success']);
					$_SESSION['errors'][$result] = TRUE;
					break;
				}
			}
			if($result == 0)
			{
				$_SESSION['success'] = 'Pomyślnie dodano produkt';
				resetAllErrorFlags();
			}
		}
		else
		{
			unset($_SESSION['success']);
			$_SESSION['errors'][$result] = TRUE;
		}

	}

	
	

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
		INNER JOIN inventory_products 
		ON ( inventory.product_id = inventory_products.id ) 
		ORDER BY inventory_products.product_name, FIELD(inventory.size,'S','M','L','XL','XXL')";
	
	$products = $DBconnection->getFromDB($query);
	
	//Save actual values in session to compare them with new values after submitting form
	foreach($products as $key=> $item)
		{
			$_SESSION['oldVals'][$item['size_id']]['price'] = $item['price'];
			$_SESSION['oldVals'][$item['size_id']]['in_store'] = $item['in_store'];
			$_SESSION['oldVals'][$item['size_id']]['in_warehouse'] = $item['in_warehouse'];
		}
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
				<button class="button" type="button" onclick="ShowAddNewProduct()">Dodaj nowy produkt</button>
				<button class="button" type="submit" form="manageWarehouse">Zapisz</button>
				<button class="button"  name="saveandclose" type="submit" form="manageWarehouse">Zapisz i wyjdź</button>

			</div>
		</div>
		<div id="newProduct">
		<form id="newProductForm" method="post" style="display:block">
		
			<p>Dodaj nowy produkt:</p>	
			<table id="newProductTable">
			<tr>
				<td>
					<button class="deleteButton" onclick="ShowAddNewProduct()" type = "button">x</button>
				</td>
				<td>
					<input type="text" name="newProduct[name]" placeholder="Nazwa">
					<input type="number" class="price" name="newProduct[price]" min="0" max="99" size="2" step="1" value="0">
					<span class="currency">
						zł
					</span>
				</td>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td>
					<select name="newProduct[sizes][size][]">
						<option value="N">N</option>
						<option value="S">S</option>
						<option value="M">M</option>
						<option value="L">L</option>
						<option value="XL">XL</option>
					</select>
					<input type="number" name="newProduct[sizes][amount][]" min="0" max="999" size="3" placeholder="Ilość">
					
				</td>
				<td>
					<button type="button" id="newSizeBtn" class="button" onclick="addNewSize(this)">Dodaj rozmiar</button>
				</td>
			</tr>
			</table>
			<button id="addNewProductBtn" type="submit" class="button">Zapisz produkt</button>
		</form>	
		
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
					<th>Usuń</th>
				</tr>
				<?php
				$productTypeOld  = '';
				foreach($products as $key=> $item)
				{
					$productType = $item['product_name'];
				?>
				<tr>
					<td>
						<?=$productType;?>
					</td>
					<td>
						<?=$item['size'];?>
					</td>
					<td>
					<?php
					//Prevent showing prcce more than once by same type products
					if($productType != $productTypeOld)
					{
					?>
						<input type="number" class="price" name="products[<?=$item['size_id'];?>][price]" min="0" max="99" step="1" value="<?=$item['price'];?>">
						<span class="currency">
							zł
						</span>
					<?php		
					}
					?>
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
					<td>
					<button type ="submit" name="productToRemove" value="<?=$item['size_id'];?>" class="deleteButton">x</button>
					</td>
				</tr>
				<?php
				$productTypeOld = $productType;
				}
				?>
			</table>
		</form>
		</div>
	</div>
	<?php 
	require_once('../../footer.php');
	showModal();	
	 ?>
	<script src="script.js"></script>
	
</body>
</html>