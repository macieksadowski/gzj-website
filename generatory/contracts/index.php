<?php


	/**
	* This is a module for users to generate a MS Word document with a contract  filled with personal data of selected person
	*/

	session_start();
	//If user isn't logged redirect to login page 
	if(!isset($_SESSION['logged']))
	{
		header('location: ../index.php');
		exit();
	}
	
	//Add database access credentials
	require_once "../connectvars-local.php";
	//Add application specific variables
	require_once "../appvars.php";
	//Add application functions
	require_once "../functions.php";
	
	//Define page name for menu file
	$PAGE_NAME = 'Generator umów';
	$CONTRACTS  = '';

	//Preprare SQL query and get data list from DB
	$query = "SELECT * FROM dane";
	$entriesArray = getFromDB($query);

	


?>




<!DOCTYPE HTML>
<html lang="pl">

<head>
    <?php 
	require_once "../../head.php"; 
?>

    <meta name="robots" content="noindex">

    <link href="../style.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <?php require_once('../menu.php'); ?>
    <main>
        <div class="wrapper">


            <div class="generator">



                <form method="post">
                    <p class="form-row">Wybierz typ umowy:

                        <input type="radio" id="zlecenie" name="contractType" value="zlecenie" checked>
                        <label class="item" for="zlecenie">Zlecenie</label>
                        <input type="radio" id="dzielo" name="contractType" value="dzielo">
                        <label class="item" for="dzielo">O dzieło</label>
                    </p>
                    <p class="form-row">Na kogo ma być wygenerowana umowa?

                        <select onchange="this.form.submit()" id="osoba" name="person">

                            <?php
				
				foreach($entriesArray as $key=>$entry) 
				{
					
					echo '<option ';
					if($key == 0 && !isset($_POST['person'])) echo 'selected ';
					else if(isset($_POST['person']) && $key == $_POST['person'])  echo 'selected ';
					echo 'value="'.$entry['id'].'">'.$entry['imie'].' '.$entry['nazwisko'].'</option>';
				}
				?>
                        </select>
                    </p>

                    <?php
					if(isset($_POST['person']))
					{
						$person = $entriesArray[$_POST['person']];
					?>
					<div id="personal-data">
						<div class="form-row">
							<label>Imię i nazwisko:</label>
							<input disabled class="data-field" type="text" id="imie-nazwisko" name="dane[imie-nazwisko]" value="<?=$person['imie'].' '.$person['nazwisko'];?>">
						</div>
						<div class="form-row">
							<label>Ulica i nr domu:</label>
							<input disabled class="data-field" type="text" id="ulica-nr_domu" name="dane[adres]" value="<?=$person['ulica'].' '.$person['nr_domu'];?>"  >
						</div>
						<div class="form-row">
							<label>Kod pocztowy:</label>
							<input disabled class="field" id="kod_pocztowy" name="dane[kod_pocztowy]" value="<?=$person['kod_pocztowy'];?>" autocomplete="off" maxlength="6" type="text" />
							<label>Miasto:</label>
							<input disabled class="data-field" type="text" id="miasto" name="dane[miasto]" value="<?=$person['miasto'];?>"  >
						</div>
						<div class="form-row">
							<label>Miasto odmienione:</label>
							<input disabled class="data-field" type="text" id="miasto" name="dane[miasto_odmienione]" value="<?=$person['miasto_odmienione'];?>"  >
						</div>
						<div class="form-row"><label>PESEL:</label>
							<input disabled class="data-field" type="text" id="PESEL" name="dane[PESEL]" value="<?=$person['PESEL'];?>"  inputmode="numeric" maxlength="11">
						</div>
						<div class="form-row"><label>Miejsce urodzenia:</label>
							<input disabled class="data-field" type="text" id="miejsce_urodzenia" name="dane[miejsce_urodzenia]" value="<?=$person['miejsce_urodzenia'];?>"  >
						</div>
						<div class="form-row">
							<label>Msc. urodzenia odmienione:</label>
							<input disabled class="data-field" type="text" id="miasto" name="dane[miejsce_urodzenia_odmienione]" value="<?=$person['miejsce_urodzenia_odmienione'];?>"  >
						</div>
						<div class="form-row"><label>Nr konta:</label>
							<input disabled class="data-field" type="text" id="nr_konta" name="dane[nr_konta]" value="<?=$person['nr_konta'];?>"  inputmode="numeric" maxlength="32">
						</div>
						<div class="form-row"><label>Urząd skarbowy:</label>
							<input disabled class="data-field" type="text" id="urzad_skarbowy" name="dane[urzad_skarbowy]" value="<?=$person['urzad_skarbowy'];?>">
						</div>
					</div>
						
					
				

                    <div class="form-footer">
                        <input name="fileName" type="text" placeholder="Nazwa pliku" onfocus="this.placeholder=''"
                            onblur="this.placeholder='Nazwa pliku'">
                        <input onclick="editdataFcn()" name="zmien" type="button" value="Zmień dane">
						<input type="button" onclick="copyFcn()" name="kopiuj" value="Kopiuj dane">
				
                        <input formaction="generate.php" name="generate" type="submit" value="Generuj dokument">
					

                    </div>
                    <div class="error">
                <?php
					}
					if(isset($_SESSION['error']))
					echo $_SESSION['error'];
				?>
                    </div>
                </form>



            </div>

            <?php require_once('../../footer.php'); ?>



            <?php

if(isset($_POST['osoba']))
{
	require_once "../script.js";
}


if(isset($_SESSION['sukces']))
{
	echo '<script>';
	echo 'alert("Pomyślnie zaktualizowano dane!")';
	echo '</script>';
	unset($_SESSION['sukces']);
}
?>

</body>

</html>