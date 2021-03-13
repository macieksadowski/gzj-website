<?php

    // This PHP file is used to update personal data of selected person

    require_once '../header.php';

    $person = $_POST['data'];

    $toUpdate = array_slice($person, 2);
    $address = $person['adres'];

    $toUpdate['ulica'] = (implode(' ', explode(' ', $address, -1)));
    $toUpdate['nr_domu'] = end(explode(' ', $address));

    $query = 'UPDATE dane SET ';
    $i = 0;
    foreach ($toUpdate as $key => $field) {
        ++$i;
        if ($i == count($toUpdate)) {
            $query .= $key.' = \''.$field.'\' ';
        } else {
            $query .= $key.' = \''.$field.'\', ';
        }
    }

    $query .= 'WHERE id = '.$_POST['person'];
    $DBconnection->sendToDBshowResult($query, 'Pomyślnie zaktualizowano dane!', 'index.php', 'index.php#error');