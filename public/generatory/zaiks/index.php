<?php
    /**
     * This is a module for users to generate a MS Word document with a list of songs and their authors.
     */
    require_once '../header.php';

    require_once '../../../restrictedPage.php';
    $page = new restrictedPage(SITE_ROOT.'/data/restrictedMenu.json');
    ob_start();

?>

<section>
    <div class="generator">
        <form action="generate.php" method="post">
            Wybierz utwory, które mają zostać wpisane do tabelki ZAiKS:
            {{LIST}}
            <div class="form-footer">
                <input name="eventName" type="text" placeholder="Nazwa wydarzenia" onfocus="this.placeholder=''"
                    onblur="this.placeholder='Nazwa wydarzenia'">
                <input name="generate" type="submit" value="Generuj dokument">
            </div>
        </form>
    </div>
</section>


<?php
$str = ob_get_clean();

//Preprare SQL query and get songs list from DB
$query = 'SELECT * FROM zaiks';
$entriesArray = $DBconnection->getFromDB($query);
$listStr = '';
foreach ($entriesArray as $key => $entry) {
    $listStr .= '<div class="item">
                <input type="checkbox" id="utwor'.$key.'" value="'.$entry['id'].'" name="songs[]">
                <label for="utwor'.$key.'">
                    '.$entry['tytul'].'</label>
            </div>';
}
$page->fillWithData($str, ['LIST' => $listStr]);
$page->show($str);