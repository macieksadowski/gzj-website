<?php

    require_once '../../restrictedPage.php';

    $page = new restrictedPage(SITE_ROOT.'/data/emptyMenu.json');
    ob_start();
?>

<section>
    <div class="loginform">
        <form action="login.php" method="post">
            <input name="login" type="text" placeholder="Login" onfocus="this.placeholder=''"
                onblur="this.placeholder='Login'">
            <input name="password" type="password" placeholder="Hasło" onfocus="this.placeholder=''"
                onblur="this.placeholder='Hasło'">
            <input name="loginBtn" type="submit" value="Zaloguj się">
        </form>
    </div>
</section>

<?php

$str = ob_get_clean();

$page->show($str);