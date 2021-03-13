<?php

    // This PHP file is used to generate MS Word document - contract filled with personal data of selected person

    require_once '../header.php';

    //If user didn't set filename return to form and show error message
    if (strlen($_POST['fileName']) < 2) {
        $_SESSION['errors'][9] = true;
        header('location: ./index.php#error');

        exit();
    }

    $outputLocation = './documents/';

    //Generate SELECT query
    $query = 'SELECT * FROM dane WHERE id = '.$_POST['person'];
    $result = $DBconnection->getFromDB($query);
    if (is_array($result)) {
        $entriesArray = $result;
        if (!empty($entriesArray)) {
            //Set type of contract (select a template) and set output file name
            //$inputFilename  = "./contracts/";
            if ('zlecenie' == $_POST['contractType']) {
                $inputFilename = 'GZJ-zlecenie.docx';
                $outputFilename = 'GZJ-zlecenie-'.$_POST['fileName'].'.docx';
            } elseif ('dzielo' == $_POST['typ']) {
                $inputFilename = 'GZJ-dzielo.docx';
                $outputFilename = 'GZJ-dzielo-'.$_POST['fileName'].'.docx';
            }

            $person = $entriesArray[0];

            //Conversion of PESEL number to Date of Birth String
            $dateOfBirth = PESELtoDate($person['PESEL']);

            //Generate strings for replacing in template

            $keywords = [];

            if ('a' == substr($person['imie'], -1)) {
                $keywords['GODNOSC'] = 'Panią';
                $keywords['SUFIX'] = 'ą';
            } else {
                $keywords['GODNOSC'] = 'Panem';
                $keywords['SUFIX'] = 'ym';
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

            $errorCode = generateDocumentFromTemplate($inputFilename, $keywords, $outputLocation, $outputFilename);
            if (!$errorCode) {
                resetAllErrorFlags();
                header('location: '.$outputLocation.$outputFilename);
            } else {
                $_SESSION['errors'][$errorCode] = true;
                header('location: index.php#error');
            }
        }
    } else {
        $_SESSION['errors'][$result] = true;
        header('location: index.php');
    }