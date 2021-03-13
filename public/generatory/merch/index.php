<?php
    /**
     * This is a module for users to manage merchandise sales and inventory.
     */
    require_once '../header.php';

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

            <div class="generator" style="flex-direction:column;">
                <div class="formtitle">
                    <button class="button"><a href="./setSaleSession.php">Nowa sprzedaż</a></button>
                    <button class="button"><a href="./manageWarehouse.php">Przegląd zapasów</a></button>
                </div>
                <div class="tableContainer">
                    <?php
                $query = 'SELECT * FROM sales_session ';
                $list = $DBconnection->getFromDB($query);
                if (is_array($list)) {
                    ?>
                    <table id="items">
                        <tr>
                            <th>
                                Id
                            </th>
                            <th>
                                Data
                            </th>
                            <th>
                                Kwota
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                        </tr>
                        <?php	foreach ($list as $key => $entry) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $entry['id']; ?>
                            </td>
                            <td>
                                <?php echo $entry['date']; ?>
                            </td>
                            <td>
                                <?php echo $entry['income']; ?>zł
                            </td>
                            <td>
                                <a href="./modifySaleSession.php?id=<?php echo $entry['id']; ?>&mode=view">
                                    Przeglądaj
                                </a>
                            </td>
                            <td>
                                <a href="./modifySaleSession.php?id=<?php echo $entry['id']; ?>&mode=activate">
                                    Uaktywnij
                                </a>
                            </td>
                            <td>
                                <a style="color:red;"
                                    href="./modifySaleSession.php?id=<?php echo $entry['id']; ?>&mode=delete">
                                    Usuń
                                </a>
                            </td>
                        </tr>
                        <?php
                    } ?>
                    </table>
                </div>
                <?php
                } else {
                    ?>
                <p>
                    Brak historii sprzedaży
                </p>
                <?php
                }
            ?>
            </div>

        </main>
        <?php require_once '../../footer.php';
    showModal();
    ?>
    </div>
</body>

</html>