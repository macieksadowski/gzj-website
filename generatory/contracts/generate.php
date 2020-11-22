<?php

	/*
	** This PHP file is used to generate MS Word document - contract filled with personal data of selected person
	*/
	
	session_start();

	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}
	
	//If user didn't set filename return to form and show error message
	if(strlen($_POST['fileName']) < 2)
	{
		$_SESSION['error'] ='Musisz podać nazwę pliku!';
		header('location: ./index.php#error');
		exit();
	}
	
	//Add database access credentials
	require_once "../connectvars-local.php";
	//Add application functions
	require_once "../functions.php";

	$outputLocation = './documents/';

	//Generate SELECT query
	$query = 'SELECT * FROM dane WHERE id = '.$_POST['person'] ;
	$entriesArray = getFromDB($query,true,"index.php#error");

	if(!empty($entriesArray))
	{
		//Set type of contract (select a template) and set output file name
		//$inputFilename  = "./contracts/";
		if($_POST['contractType'] == 'zlecenie')
		{
			$inputFilename = 'GZJ-zlecenie.docx';
			$outputFilename = 'GZJ-zlecenie-'.$_POST['fileName'].'.docx';
		}
		else if($_POST['typ'] == 'dzielo')
		{
			$inputFilename = 'GZJ-dzielo.docx';
			$outputFilename = 'GZJ-dzielo-'.$_POST['fileName'].'.docx';
		}

		
		$person = $entriesArray[0];

		//Conversion of PESEL number to Date of Birth String
		$dateOfBirth = PESELtoDate($person['PESEL']);
		

		//Generate strings for replacing in template
			
		$keywords = array();

		if(substr($person['imie'], -1) == 'a')
		{
			$keywords['GODNOSC'] = "Panią";
			$keywords['SUFIX'] = "ą";
		}
		else
		{
			$keywords['GODNOSC'] = "Panem";
			$keywords['SUFIX'] = "ym";
		}
		$keywords['IMIE_ODMIENIONE'] = $person['imie_odmienione'];
		$keywords['IMIE'] = $person['imie'];
		$keywords['NAZWISKO_ODMIENIONE'] = $person['nazwisko_odmienione'];
		$keywords['NAZWISKO'] = $person['nazwisko'];
		$keywords['MIASTO_ODMIENIONE'] = $person['miasto_odmienione'];
		$keywords['MIASTO'] = $person['miasto'];
		$keywords['KOD_POCZTOWY'] = $person['kod_pocztowy'];
		$keywords['ULICA'] = $person['ulica'];
		$keywords['NR_DOMU'] = $person['nr_domu'];
		$keywords['NR_PESEL'] = $person['PESEL'];
		$keywords['MIEJSCE_URODZENIA_ODMIENIONE'] = $person['miejsce_urodzenia_odmienione'];
		$keywords['MIEJSCE_URODZENIA'] = $person['miejsce_urodzenia'];
		$keywords['NR_KONTA'] = $person['nr_konta'];
		$keywords['URZAD_SKARBOWY'] = $person['urzad_skarbowy'];
		$keywords['DATA_URODZENIA'] = $dateOfBirth;
		

		

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
		
						
			
	
?>