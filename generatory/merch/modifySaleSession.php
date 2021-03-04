<?php
    /**
     * This file starts new sale session.
     */
    require_once '../header.php';

    if (isset($_GET['mode'], $_GET['id'])) {
        $mode = $_GET['mode'];
        $id = $_GET['id'];

        if ('delete' == $mode) {
            $query = "DELETE FROM sales_session WHERE id = {$id}";
            $success = 'Usunięto sesję sprzedażową (id:'.$id.') !';
            $DBconnection->sendToDBshowResult($query, $success, 'index.php', 'index.php#error');
        }
        if ('activate' == $mode) {
            $query = "UPDATE sales_session SET active = true WHERE id = {$id}";

            $DBconnection->sendToDBshowResult($query, 'Rozpoczeto sesję!');
            $query = 'SELECT id,income FROM sales_session WHERE active = true';
            $result = $DBconnection->getFromDBShowErrors($query, 'index.php#error');
            $successPath = 'saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income'];

            header('location: saleSession.php?saleSession='.$result[0]['id'].'&sessionIncome='.$result[0]['income']);

            exit();
        }
        if ('view' == $mode) {
            $query = "SELECT transactions.product_type, transactions.product, transactions.amount, inventory.size_id, inventory.size, inventory_products.product_name, inventory_products.price FROM transactions INNER JOIN( inventory INNER JOIN inventory_products ON( inventory.product_id = inventory_products.id ) ) ON ( transactions.product = inventory.size_id ) WHERE transactions.session_id = {$id} ORDER BY inventory.size_id ASC";
            $result = $DBconnection->getFromDBShowErrors($query, 'index.php#error'); ?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <?php require_once '../../head.php'; ?>

    <meta name="robots" content="noindex">

    <link href="../style.css" rel="stylesheet" type="text/css" />
    <link href="../modal.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- This div is used as container for whole page-->
    <div class="page-container">
        <?php require_once '../menu.php'; ?>
        <main>
            <div class="content">
                <div class="generator">
                    <div class="formtitle">
                        <h1>Podsumowanie</h1>
                        <p>sprzedaż nr <?php echo $id; ?></p>
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
            $lastEntry = count($result) - 1;
            $amount = 0;

            foreach ($result as $key => $entry) {
                $id = $entry['size_id'];
                $nextId = $entry['size_id'];
                if ($key != $lastEntry) {
                    $nextId = $result[$key + 1]['size_id'];
                }
                if ($nextId != $id) {
                    echo '<tr>';
                    echo '<td>'.$entry['product_name'].'</td>';
                    echo '<td>'.$entry['size'].'</td>';
                    echo '<td>'.$entry['price'].' zł</td>';
                    $money = $amount * $entry['price'];
                    echo '<td>'.$amount.'</td>';
                    echo '<td>'.$money.' zł</td>';
                    echo '</tr>';
                    $amount = 0;
                } else {
                    $amount += $entry['amount'];
                    if ($key == $lastEntry) {
                        echo '<tr>';
                        echo '<td>'.$entry['product_name'].'</td>';
                        echo '<td>'.$entry['size'].'</td>';
                        echo '<td>'.$entry['price'].' zł</td>';
                        $money = $amount * $entry['price'];
                        echo '<td>'.$amount.'</td>';
                        echo '<td>'.$money.' zł</td>';
                        echo '</tr>';
                    }
                }
            } ?>

                    </table>
                </div>
            </div>
        </main>
        <?php require_once '../../footer.php';
            showModal(); ?>
    </div>
</body>

</html>




<?php
        }
    } else {
    $_SESSION['errors'][0] = true;
    header('location: index.php#error');
}

?>