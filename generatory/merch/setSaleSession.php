<?php
 	/**
	* This file starts new sale session
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
    /************************************************/

    if(!isset($_GET['saleSession']))
    {
        $query = "INSERT INTO sales_session VALUES (0,curdate(),true,0)";
        $DBconnection->sendToDBshowResult($query,'Rozpoczęto sesję');
        $query = "SELECT id,income FROM sales_session WHERE active = true";
        $result = $DBconnection->getFromDBShowErrors($query,'index.php#error');
        header('location: saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income']);	
    }
    else
    {
        $id = $_GET['saleSession'];
        $query = 'UPDATE sales_session SET active = false WHERE id='.$id;
        $success = 'Zakończono sprzedaż nr '.$id;
        $DBconnection->sendToDBshowResult($query,$success,'index.php','index.php');
    }
	
	
	




   

?>