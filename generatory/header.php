<?php

//Add DB credentials and methods
require_once '../database.php';
session_start();

//If user isn't logged redirect to login page
if (!isset($_SESSION['logged'])) {
    header('location: ../index.php');

    exit();
}

//Add application specific variables
require_once '../appvars.php';
//Add application functions
require_once '../functions.php';

// VARIABLES
$errors = @$_SESSION['errors'];
$DBconnection = $_SESSION['DBConnection'];

//Define page name for menu file
$page_info = json_decode(file_get_contents('./page.json'));
$PAGE_NAME = $page_info->PAGE_NAME;