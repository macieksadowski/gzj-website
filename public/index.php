<?php
require_once '../page.php';
$page = new page();
$socialLinks = $page->menu->getLinks();
ob_start();

?>

<section>
    <h2>Przed państwem</h2>
    <div id="logoBig">
        <img src="./assets/img/logo-header.gif">
    </div>
</section>
<section>
    <div class="socials">
        <h3>Znajdź nas na:</h3>
        <div id="socials-grid">
            <a class="social" href="<?php echo $socialLinks['FB']; ?>" target=" _blank">
                <i class="icon-facebook-official fb"></i>
                <label>Facebook</label>
            </a>
            <a class="social" href="<?php echo $socialLinks['IG']; ?>" target=" _blank">
                <i class="icon-instagram ig"></i>
                <label>Instagram</label>
            </a>
            <a class="social" href="<?php echo $socialLinks['YT']; ?>" target=" _blank">
                <i class="icon-youtube-play yt"></i>
                <label>YouTube</label>
            </a>
            <a class="social" href="<?php echo $socialLinks['SP']; ?>" target=" _blank">
                <i class="icon-spotify sp"></i>
                <label>Spotify</label>
            </a>
            <a class="social" href="<?php echo $socialLinks['SC']; ?>" target=" _blank">
                <i class="icon-soundcloud sc"></i>
                <label>SoundCloud</label>
            </a>
        </div>
    </div>
</section>



<?php

$str = ob_get_clean();

$page->show($str);