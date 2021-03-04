<?php
    /**
     * This is a module for users to to sell (update the number of items in the store).
     */
    require_once './header.php';

    //If sale session not started don't allow to enter page
    if (isset($_GET['saleSession'], $_GET['sessionIncome'])) {
        $sessionId = $_GET['saleSession'];
        $income = $_GET['sessionIncome'];
    } else {
        header('location: ./index.php');

        exit();
    }

    //Preprare SQL query and get products list from DB
    $query = 'SELECT * FROM inventory_products';
    $products = $DBconnection->getFromDB($query);

    //Remember selected product after submission of form
    if (isset($_POST['product'])) {
        $selectedProduct = $_POST['product'];
    }

    //Register new transaction
    if (isset($_POST['sell'])) {
        $amount = $_POST['amount'];
        $oldAmount = $_POST['sell'];
        $size = $_POST['size'];

        $newAmount = $oldAmount - $amount;

        $queries = ['UPDATE inventory SET in_store = '.$newAmount.' WHERE size_id = '.$size,
            "INSERT INTO transactions VALUES (0,{$sessionId},{$selectedProduct},{$size},{$amount})",
        ];
        $DBconnection->sendToDBshowResult($queries, '');

        $query = "SELECT inventory_products.price,transactions.amount FROM transactions INNER JOIN inventory_products ON(inventory_products.id = transactions.product_type) WHERE transactions.session_id = {$sessionId}";
        $result_array = $DBconnection->getFromDB($query);
        foreach ($result_array as $entry) {
            $income += $entry['price'] * $entry['amount'];
        }
        $query = "UPDATE sales_session SET income = {$income} WHERE id = {$sessionId}";
        $DBconnection->sendToDBshowResult($query, 'Sprzedaż zarejestrowana!');
        $_SESSION['sessionIncome'] = $income;
    }
?>




<!DOCTYPE HTML>
<html lang="pl">

<head>
    <?php require_once '../../head.php';
?>
    <!-- Inform robots to don't index this page-->
    <meta name="robots" content="noindex">
    <!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <link href="../modal.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="page-container">
        <?php require_once '../menu.php'; ?>
        <main>

            <div class="generator">
                <form id="new-sale" method="post">
                    <div class="formtitle">
                        <h1>
                            Nowa sprzedaż (nr <?php echo $sessionId; ?>)
                        </h1>
                        <div>
                            Kwota sprzedaży: <?php echo $income; ?>zł
                        </div>
                        <button class="button"><a
                                href="./setSaleSession.php?saleSession=<?php echo $sessionId; ?>">Zakończ
                                sprzedaż</a></button>
                    </div>

                    <p>Wybierz produkt:</p>
                    <div>
                        <select onchange="this.form.submit()" name="product">
                            <?php	//Generate dropdown list
                    foreach ($products as $key => $entry) {
                        ?>
                            <option <?php
                        //Select first position if nothing selected
                        if (0 == $key && !isset($selectedProduct)) {
                            echo 'selected ';
                            $selectedProduct = 1;
                        }
                        //Else mark as selected previous selected product
                        elseif (isset($selectedProduct) && $entry['id'] == $selectedProduct) {
                            echo 'selected ';
                        } ?> value="<?php echo $entry['id']; ?>">
                                <?php echo $entry['product_name']; ?>
                            </option>

                            <?php
                    }
                    ?>
                        </select>
                        <select onchange="setMaxAmount(this)" name="size">
                            <?php
                    //Preprare SQL query and get size list for selected product
                    $query = 'SELECT inventory.size,
						inventory.size_id,
						inventory.in_store,
						inventory.in_warehouse,
						inventory.product_id 
						FROM inventory 
						INNER JOIN inventory_products 
						ON( inventory.product_id = inventory_products.id ) 
						WHERE inventory_products.id = '.$selectedProduct;

                    $sizes = $DBconnection->getFromDB($query);

                    //Add options to dropdown  list
                    foreach ($sizes as $key => $entry) {
                        ?>
                            <option <?php
                        //Select first position if nothing selected
                        if (0 == $key) {
                            echo 'selected ';
                            $maxAmount = $entry['in_store'];
                        }

                        echo 'value="'.$entry['size_id'].'">';
                        if (count($sizes) < 2) {
                            echo $entry['in_store'].'szt.';
                        } else {
                            echo $entry['size'].' ('.$entry['in_store'].'szt.)';
                        }

                        echo '</option>';
                    }
                    ?> </select>

                                <input name="amount" type="number" min="0" max="<?php echo $maxAmount; ?>" size="2"
                                    placeholder="Ilość" onchange="disableSell(this)">
                                <button id="sellBtn" disabled onclick="this.form.submit()" class="button" name="sell"
                                    value="<?php echo $maxAmount; ?>"> Sprzedaj</button>
                    </div>
                </form>

                <table id="items">
                    <tr>
                        <th>
                            Nazwa
                        </th>
                        <th>
                            Cena
                        </th>
                        <th>
                            S
                        </th>
                        <th>
                            M
                        </th>
                        <th>
                            L
                        </th>
                        <th>
                            XL
                        </th>
                        <?php
            //Show preview of products amount as a table
                $query = "SELECT * FROM inventory_products 
					INNER JOIN inventory 
					ON( inventory_products.id = inventory.product_id ) 
					ORDER BY inventory_products.product_name, FIELD(inventory.size,'S','M','L','XL','XXL')";
                $list = $DBconnection->getFromDB($query);
                $prev_id = 0;
                foreach ($list as $key => $entry) {
                    $id = $entry['id'];
                    if ($prev_id != $id) {
                        ?>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry['product_name']; ?>
                        </td>
                        <td>
                            <?php echo $entry['price']; ?> zł
                        </td>
                        <?php
                    } ?>
                        <td>
                            <?php echo $entry['in_store']; ?>
                        </td>
                        <?php	$prev_id = $id;
                }
                ?>
                    </tr>
                </table>
            </div>

        </main>
        <?php require_once '../../footer.php';
        showModal();
    ?>

        <script src="script.js"></script>
    </div>
</body>

</html>