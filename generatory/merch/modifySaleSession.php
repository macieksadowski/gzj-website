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
    
    
	//Define page name for menu file
	$PAGE_NAME = 'Sprzedaż gadżetów';
	$MERCH  = '';


    /************************************************/

    if(isset($_GET['mode']) && isset($_GET['id']))
    {
        $mode = $_GET['mode'];
        $id = $_GET['id'];

        if($mode == 'delete')
        {
            $query = "DELETE FROM sales_session WHERE id = $id";
            $success = 'Usunięto sesję sprzedażową (id:'.$id.') !';
            $DBconnection->sendToDBshowResult($query,$success,'index.php','index.php#error');
        }
        if($mode == 'activate')
        {
            $query = "UPDATE sales_session SET active = true WHERE id = $id";
                        
            $DBconnection->sendToDBshowResult($query,'Rozpoczeto sesję!');
            $query = "SELECT id,income FROM sales_session WHERE active = true";
            $result = $DBconnection->getFromDBShowErrors($query,'index.php#error');           
                    $successPath = 'saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income'];
           
            header('location: saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income']);
            exit();   
        }
        if($mode == 'view')
        {
            $query = "SELECT transactions.product_type, transactions.product, transactions.amount, inventory.size_id, inventory.size, inventory_products.product_name, inventory_products.price FROM transactions INNER JOIN( inventory INNER JOIN inventory_products ON( inventory.product_id = inventory_products.id ) ) ON ( transactions.product = inventory.size_id ) WHERE transactions.session_id = $id ORDER BY inventory.size_id ASC";
            $result = $DBconnection->getFromDBShowErrors($query,'index.php#error');
            
?>

    <!DOCTYPE HTML>
    <html lang="pl">
    <head>
    <?php 
        require_once "../../head.php"; 
    ?>
    
    <meta name="robots" content="noindex">
    
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <link href="../modal.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
    <!-- This div is used as container for whole page-->
    <div class="page-container">
        <?php require_once('../menu.php'); ?>
        <main>	
        <div class="content">
            <div class="generator">
                <div class="formtitle">
                    <h1>Podsumowanie</h1>
                    <p>sprzedaż nr <?=$id;?></p>
                    <button class="button"><a href="./index.php">Powrót</a></button>
                </div>

                <table id="items">
                    <tr>
                        <th>Nazwa</th>
                        <th>Rozmiar</th>
                        <th>Cena szt.</th>
                        <th>Ilość</th>
                        <th>Kwota</th>
                    </tr>   
            <?php
            $lastEntry = count($result)-1;
            $amount = 0;
            
            foreach($result as $key=>$entry) 
            {
                
                $id = $entry['size_id'];
                $nextId = $entry['size_id'];
                if($key != $lastEntry)
                {
                    $nextId = $result[$key+1]['size_id'];
                }
                if($nextId != $id)
                {
                    echo '<tr>';
                    echo '<td>'.$entry['product_name'].'</td>';
                    echo '<td>'.$entry['size'].'</td>';
                    echo '<td>'.$entry['price'].' zł</td>';
                    $money = $amount*$entry['price'];
                    echo '<td>'.$amount.'</td>';
                    echo '<td>'.$money.' zł</td>';
                    echo '</tr>';
                    $amount = 0;
                }
                else
                {
                    $amount += $entry['amount'];
                    if($key == $lastEntry)
                    {
                        echo '<tr>';
                        echo '<td>'.$entry['product_name'].'</td>';
                        echo '<td>'.$entry['size'].'</td>';
                        echo '<td>'.$entry['price'].' zł</td>';
                        $money = $amount*$entry['price'];
                        echo '<td>'.$amount.'</td>';
                        echo '<td>'.$money.' zł</td>';
                        echo '</tr>';
                    }
                }
                
            
            }
            
            ?>
            
                </table>
            </div>
	    </div>
	    </main>
        <?php require_once('../../footer.php');
        showModal();	
         ?>
    </div>
    </body>
    </html>




<?php
    }
}
else
{
    $_SESSION['errors'][0] = TRUE;
    header('location: index.php#error');
}

?>







	




   

