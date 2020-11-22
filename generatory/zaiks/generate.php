<?php
	/*
	** This PHP file is used to generate MS Word document with a list of selected songs with info about composer and textwriter
	*/
	session_start();

	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}
	
	//If user didn't select any song return to form and show error message
	if(!isset($_POST['songs']))
	{
		$_SESSION['error'] ='Nie wybrano żadnego utworu!';
		header('location: ./index.php#error');
		exit();
	}
	
	//Add database access credentials
	require_once "../connectvars-local.php";
	//Add application functions
	require_once "../functions.php";

	$outputLocation = './documents/';
	
	//Generate SELECT query
	$query = 'SELECT * FROM zaiks WHERE id IN (';
	foreach($_POST['songs']  as $key=>$item) 
	{
		$query .= $item;
		
		if($key < (count($_POST['songs']))-1) 
		{
			$query .= ',';
		}
	}		
	$query .= ')' ;

	$entriesArray = getFromDB($query,true,"index.php#error");


	if(!empty($entriesArray))
	{
		//Set file name
		$inputFilename = 'ZAiKS-GZJ.docx';
		if(strlen($_POST['eventName'])>1)
			$outputFilename = 'GZJ-ZAiKS-'.$_POST['eventName'].'.docx';
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
			header('location: '.$outputLocation.$outputFilename);
		}
		else
		{
			$_SESSION['error'] = $errorCode;
			header('location: index.php#error');
		}
	}
				
	//TODO dodać stronę z listą już wygenerowanych plików	

	
?>