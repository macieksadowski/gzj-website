<?php
	
	/**
	* This file contains PHP code for login operation. It processes data and redirects to client panel or to login page if login operation fails.
	*/
	
	//Add database access credentials
	require_once "connectvars.php";
	require_once "database.php";
	session_start();
	
	//Add application specific variables
	require_once "appvars.php";
	//Add application functions
	require_once "functions.php";

	//If user is logged redirect to client panel
	if($_SESSION['logged'] == TRUE)
	{
		header('location: '.HOME_SITE);
		exit();
	}


	/*VARIABLES
	* **********************************************
	*/
	$_SESSION['logged'] = FALSE;
	$DBconnection =  new Database(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$_SESSION['DBConnection'] = $DBconnection;
	$errors = $_SESSION['errors'];

	//Read user credentials from login page fields
	if(!empty($_POST['login']))
	{
		$login = $_POST['login'];
	}
	else
	{
		$_SESSION['errors'][3] = TRUE;
		header('Location: ./index.php');
		exit;
	}
	if(!empty($_POST['password']))
	{
		$password = $_POST['password'];
	}
	else
	{
		$_SESSION['errors'][4] = TRUE;
		header('Location: ./index.php');
		exit;
	}
	
	$result = $DBconnection->performLogin($login,$password);
	if(is_array($result))
	{
		$_SESSION['logged'] = TRUE;
		$_SESSION['id'] = $result[0];
		$_SESSION['user'] = $result[1];	
		resetAllErrorFlags();
		header('location: '.HOME_SITE);
	}
	else
	{
		$_SESSION['errors'][$result] = TRUE;
		header('location: index.php');
	}
	

	
?>