<?php
	/*
	** This PHP file is used to generate MS Word document with a list of selected songs with info about composer and textwriter
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
	$errors = $_SESSION['errors'];
	$DBconnection = $_SESSION['DBConnection'];

	//If user didn't select any song return to form and show error message
	if(!isset($_POST['songs']))
	{
		$_SESSION['errors'][6] = TRUE;
		header('location: ./index.php#error');
		exit();
	}
	else  $songs = $_POST['songs'];
		
	$name = $_POST['eventName'];

	$outputLocation = './documents/';

	/************************************************/

	
	

	
	//Generate SELECT query
	$query = 'SELECT * FROM zaiks WHERE id IN (';
	foreach($songs  as $key=>$item) 
	{
		$query .= $item;
		
		if($key < (count($songs))-1) 
		{
			$query .= ',';
		}
	}		
	$query .= ')' ;

	$result = $DBconnection->getFromDB($query);
	if(is_array($result))
	{
		$entriesArray = $result;
		if(!empty($entriesArray))
		{
			//Set file name
			$inputFilename = 'ZAiKS-GZJ.docx';
			if(strlen($name)>1)
				$outputFilename = 'GZJ-ZAiKS-'.$name.'.docx';
			//If name was not given by user use date as file name
			else 
			{	
				$actualTimestamp = new DateTime();
				$actualTime = strftime('%e-%m-%y-%H-%M',$actualTimestamp->getTimestamp());
				$outputFilename = 'GZJ-ZAiKS-'.$actualTime.'.docx';;
			}

			//Generate table form template
			$to_replace = "";
			foreach($entriesArray as $key=>$entry)
			{
				
				$row = file_get_contents('./row-template.txt');
				$row = str_replace('ID',($key+1),$row);
				$row = str_replace('TYTUL',$entry['tytul'],$row);
				$row = str_replace('KOMPOZYTOR',$entry['kompozytor'],$row);
				$row = str_replace('AUTOR',$entry['autor_tekstu'],$row);
				$to_replace .= $row;
			}

			$keywords = array('TO_REPLACE'=>$to_replace);
			$errorCode = generateDocumentFromTemplate($inputFilename,$keywords,$outputLocation,$outputFilename);
			if(!$errorCode)
			{
				resetAllErrorFlags();
				header('location: '.$outputLocation.$outputFilename);
			}
			else
			{
				$_SESSION['errors'][$errorCode] = TRUE;
				header('location: index.php#error');
			}
		}
	}
	else
	{
		$_SESSION['errors'][$result] = TRUE;
		header('location: index.php');
	}

	
				
	//TODO dodać stronę z listą już wygenerowanych plików	

	
?>