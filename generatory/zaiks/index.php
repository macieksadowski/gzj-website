<?php
	/**
	* This is a module for users to generate a MS Word document with a list of songs and their authors
	*/

	//Add DB credentials and methods
	require_once "../database.php";
	session_start();

	//Add application specific variables
	require_once "../appvars.php";
	//Add application functions
	require_once "../functions.php";

	//If user isn't logged redirect to login page
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}
	
	
	/*VARIABLES
	* **********************************************
	*/
	$errors = @$_SESSION['errors'];
	$DBconnection = $_SESSION['DBConnection'];

	//Define page name for menu file
	$PAGE_NAME = 'Generator ZAiKS';
	$ZAIKS  = '';

	//Preprare SQL query and get songs list from DB
	$query = "SELECT * FROM zaiks";
	$entriesArray = $DBconnection->getFromDB($query);
	
	/************************************************/
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
			<form action="generate.php" method="post">
				Wybierz utwory, które mają zostać wpisane do tabelki ZAiKS:
				<?php
				foreach($entriesArray as $key=>$entry) 
				{
				?>
				<div class="item">
					<input type="checkbox" id="utwor<?=$key;?>" value="<?=$entry['id'];?>" name="songs[]">
					<label for="utwor<?=$key;?>">
						<?=$entry['tytul'];?>
					</label>
				</div>
				<?php
				}
				?>
				<div class="form-footer">
					<input name="eventName" type="text" placeholder="Nazwa wydarzenia" onfocus="this.placeholder=''" onblur="this.placeholder='Nazwa wydarzenia'">
 					<input name="generate" type="submit"value="Generuj dokument">
				</div>
			</form> 
		</div>
	</div>
	</main>
	<?php require_once('../../footer.php');
	showModal();
	?>
</div>
</body>
</html>



