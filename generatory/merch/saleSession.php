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

	//If sale session not started don't allow to enter page
	if(isset($_GET['saleSession']) && isset($_GET['sessionIncome']))
    {
		$sessionId = $_GET['saleSession'];
		$income = $_GET['sessionIncome'];
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
	$query = "SELECT * FROM inventory_products";
	$products = $DBconnection->getFromDB($query);
	
	if(isset($_POST['size']))
	{
		$selectedSize = $_POST['size'];
	}
	if(isset($_POST['product']))
	{
		$selectedProduct = $_POST['product'];
		if(@$_SESSION['selectedProduct'] != $selectedProduct)
		{
			$_SESSION['selectedProduct'] = $selectedProduct;
			unset($selectedSize);
		}
	}
	
	
	
	if(isset($_POST['sell']))
	{
		$amount = $_POST['amount'];
		$oldAmount = $_POST['sell'];
	}

	/************************************************/

	//$income = $_SESSION['sessionIncome'];
	//Register new transaction
	if(isset($amount))
	{
		$size = $_POST['size'];
		$newAmount = $oldAmount - $amount;
		$query = 'UPDATE inventory SET in_store = '.$newAmount.' WHERE size_id = '.$size;
		$result = $DBconnection->sendToDB($query);  
		if($result == 0)
		{
			$query = "INSERT INTO transactions VALUES (0,$sessionId,$selectedProduct,$size,$amount)";
			$result = $DBconnection->sendToDB($query);
			if($result == 0)
			{	
				$_SESSION['success'] = TRUE;
				resetAllErrorFlags();
			}
			else
			{
				unset($_SESSION['success']);
				$_SESSION['errors'][$result] = TRUE;
			}
		}
		else
		{
			unset($_SESSION['success']);
			$_SESSION['errors'][$result] = TRUE;
		}
	}
	
	
	if(isset($_SESSION['success']))
	{
		$query = "SELECT inventory_products.price,transactions.amount FROM transactions INNER JOIN inventory_products ON(inventory_products.id = transactions.product_type) WHERE transactions.session_id = $sessionId";
				$result_array = $DBconnection->getFromDB($query);
				foreach($result_array as $entry)
				{
					$income += $entry['price'] * $entry['amount'];
				}
				$query = "UPDATE sales_session SET income = $income WHERE id = $sessionId";
				$result = $DBconnection->sendToDB($query);
				if($result == 0)
				{	
				$_SESSION['sessionIncome'] = $income;
				resetAllErrorFlags();
				}
				else
				{
					unset($_SESSION['success']);
					$_SESSION['errors'][$result] = TRUE;
				}
	}
?>




<!DOCTYPE HTML>
<html lang="pl">
<head>
<?php 
	require_once "../../head.php"; 
?>
<!-- Inform robots to don't index this page-->
<meta name="robots" content="noindex">
<!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
<link href="../style.css" rel="stylesheet" type="text/css" />
<link href="../modal.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- This div is used as container for whole page-->
<div class="page-container">
	<?php require_once('../menu.php'); ?>
	<main>
	<div class="content">
		<div class="generator">
			<form id="new-sale" method="post" >
				<div class="formtitle">
					<h1>
						Nowa sprzedaż (nr <?=$sessionId;?>)
					</h1>
					<div>
						Kwota sprzedaży: <?=$income;?>zł
					</div>
					<button class="button"><a href="./setSaleSession.php?saleSession=<?=$sessionId;?>">Zakończ sprzedaż</a></button>
				</div>
			
				<p>Wybierz produkt:
					<select onchange="this.form.submit()" name="product">
					<?php	
						//Generate dropdown list 
						foreach($products as $key=>$entry) 
						{
							echo '<option ';
							//Select first position if nothing selected
							if($key == 0 && !isset($selectedProduct)) 
							{
								echo 'selected ';
								$selectedProduct = 1;
							}
							else if(isset($selectedProduct) && $key+1 == $selectedProduct)  echo 'selected ';
							
							echo 'value="'.$entry['id'].'">';
							echo $entry['product_name'];
							echo '</option>';
						}
					?>
					</select>
					<select onchange="this.form.submit()" name="size">
					<?php
						//Preprare SQL query and get size list for selected product
						$query = 'SELECT inventory.size,
							inventory.size_id,
							inventory.in_store,
							inventory.in_warehouse,
							inventory.product_id 
							FROM inventory 
							INNER JOIN inventory_products 
							ON( inventory.product_id = inventory_products.id ) 
							WHERE inventory_products.id = '.$selectedProduct;
						
						$sizes = $DBconnection->getFromDB($query);
						//If only one size - disable input
						//if(count($sizes) < 2)  echo ' disabled >';
						//else 
						//echo ' >';
						//Add options to dropdown  list
						foreach($sizes as $key=>$entry) 
						{
							echo '<option ';
							//Select first position if nothing selected
							if($key == 0 && !isset($selectedSize)) 
								{
									echo 'selected ';
									$selectedProduct = 1;
									$maxAmount = $entry['in_store'];
								}
							else if(isset($selectedSize) && $entry['size_id'] == $selectedSize) 
							{
								echo 'selected ';
								$maxAmount = $entry['in_store'];
							}
							echo 'value="'.$entry['size_id'].'">';
							if(count($sizes) < 2)  echo $entry['in_store'].'szt.';
							else echo $entry['size'].' ('.$entry['in_store'].'szt.)';
							echo '</option>';
						}
					?>
					</select>
			
					<input name="amount" type="number" min="0" max="<?=$maxAmount;?>" size="2" placeholder="Ilość" onchange="disableSell(this)" >
					<button id="sellBtn" disabled onclick="this.form.submit()" class="button" name="sell" value="<?=$maxAmount;?>"> Sprzedaj</button>
				</p>
			</form>
			
			<table id="items">
				<tr>
					<th>
						Nazwa
					</th>
					<th>
						Cena
					</th>
					<th>
						S
					</th>
					<th>
						M
					</th>
					<th>
						L
					</th>
					<th>
						XL
					</th>
				<?php
					$query = 'SELECT * FROM inventory_products 
						INNER JOIN inventory 
						ON( inventory_products.id = inventory.product_id )';
					$list = $DBconnection->getFromDB($query);
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
		</div>
	</div>
	</main>
	<?php 
		require_once('../../footer.php');
		showModal();	
	?>
</div>
<script src="script.js"></script>

</body>
</html>