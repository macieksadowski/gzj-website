<?php

    /**
     * This file starts new sale session.
     */
    require_once '../header.php';

    if (!isset($_GET['saleSession'])) {
        $query = 'INSERT INTO sales_session VALUES (0,curdate(),true,0)';
        $DBconnection->sendToDBshowResult($query, 'Rozpoczęto sesję');
        $query = 'SELECT id,income FROM sales_session WHERE active = true';
        $result = $DBconnection->getFromDBShowErrors($query, 'index.php#error');
        header('location: saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income']);
    } else {
        $id = $_GET['saleSession'];
        $query = 'UPDATE sales_session SET active = false WHERE id='.$id;
        $success = 'Zakończono sprzedaż nr '.$id;
        $DBconnection->sendToDBshowResult($query, $success, 'index.php', 'index.php');
    }