<?php

	/**
	* This is a PHP file to update amount of products in DB
	*/
	session_start();
	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}

	//Add database access credentials
	require_once "../connectvars.php";
	//Add application specific variables
	require_once "../appvars.php";
	//Add application functions
	require_once "../functions.php";
	
	//Define page name for menu file
	$PAGE_NAME = 'Sprzedaż gadżetów';
	$MERCH  = '';
	
    if(isset($_POST['sell']))
    {   
        //Preprare SQL query and send new data to DB
        $query = 'UPDATE inventory_sizes SET in_store = in_store - '.$_POST['amount'].' WHERE item_id = '.$_POST['product'].' AND size = "'.$_POST['size'].'"';
        sendToDB($query,true,"./new-sale-session.php");  
    }



?>