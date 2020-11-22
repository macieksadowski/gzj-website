<?php
	
	/**
	* This file contains PHP code for login operation. It processes data and redirects to client panel or to login page if login operation fails.
	*/

	session_start();
	//If user isn't logged redirect to login page 
	if((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('location: index.php');
		exit();
	}
	
	//Add database access credentials
	require_once "connectvars-local.php";
	//Add application specific variables
	require_once "appvars.php";
	$ERROR_LIST = json_decode(file_get_contents('./errors.json',FILE_USE_INCLUDE_PATH));


	//Estabilish new connection with DB
	$DBconnection = @new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	
	//If connection fails show error communicate
	if ($DBconnection->connect_errno!=0)
	{
		//$_SESSION['error'] ='Bład bazy danych ('.$DBconnection->connect_errno.')';
		$_SESSION['error'] = 1;
		header('location: index.php');
	}
	else
	{
		//Read user credentials from login page fields
		$login = $_POST['login'];
		$password = $_POST['password'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
		//Search if in DB exists user with given username
		if($results = @$DBconnection->query(sprintf("SELECT * FROM users WHERE user='%s'",mysqli_real_escape_string($DBconnection,$login))))
		{
			$usersFound = $results->num_rows;
			
			//If username is found, check if password is correct
			if($usersFound > 0)
			{
				$row = $results->fetch_assoc();
				
				//If password is correct redirect to client panel
				if(password_verify($password,$row['pass']))
				{
					$_SESSION['logged'] = true;
					$_SESSION['id'] = $row['id'];
					$_SESSION['user'] = $row['user'];	
					unset($_SESSION['error']);
					$results->free_result();
					header('location: '.HOME_SITE);
				}
				//Else return to login page and show error message
				else
				{
				$_SESSION['error'] = 3;
				header('location: index.php');
				}	
			}
			//Else return to login page and show error message
			else
			{
				$_SESSION['error'] = 4;
				header('location: index.php');
			}		
		}
		$DBconnection->close();
	}

	
?>