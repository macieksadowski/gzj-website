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
        $result = $DBconnection->sendToDB($query);
        if($result == 0)
	    {
            $query = "SELECT id,income FROM sales_session WHERE active = true";
            $result = $DBconnection->getFromDB($query);
            resetAllErrorFlags();
            header('location: saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income']);
            
	    }
        else
        {
            $_SESSION['errors'][$result] = TRUE;
            header('location: index.php#error');
        }
		
    }
    else
    {
        $id = $_GET['saleSession'];
        $query = 'UPDATE sales_session SET active = false WHERE id='.$id;
        $result = $DBconnection->sendToDB($query);
        $_SESSION['success'] = 'Zakończono sprzedaż nr '.$id;
		resetAllErrorFlags();
        header('location: index.php');
    }
	
	
	




   

?>