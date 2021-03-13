<?php
require_once '../../page.php';

require_once 'yt-links.php';
$page = new page();
$socialLinks = $page->menu->getLinks();
$pageInfo = $page->getPageInfo();

ob_start();

?>

<!-- This is a container for main content of page. -->
<div class="content">
    <div class="title">
        <h1><?php echo $pageInfo['PAGE_NAME']; ?></h1>
    </div>
    <section>
        <div class="records-stripe">
            <div class="records-name">
                <h1>Koncerty</h1>
            </div>
            <div class="records-list">
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $live1iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $live2iframe; ?>"></div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="records-content">
            <div class="records-description">
                <div class="records-name">
                    <h3 style="margin-block-end:0">
                        2020
                    </h3>
                    <h1 style="margin-block-start:0">
                        Pałac Jaśminowy
                    </h1>
                </div>
                <div class="records-cover">
                    <img src="<?php echo $jasminCover; ?>">
                </div>
            </div>
            <div class="records-list">
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $jasmin1iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $jasmin2iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $jasmin3iframe; ?>"></div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="records-content">
            <div class="records-description">
                <div class="records-name">
                    <h3 style="margin-block-end:0">
                        2019
                    </h3>
                    <h1 style="margin-block-start:0">
                        Orle Gniazdo
                    </h1>
                </div>
                <div class="records-cover">
                    <img src="<?php echo $orleCover; ?>">
                </div>
            </div>
            <div class="records-list">
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $orle1iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $orle2iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $orle3iframe; ?>"></div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="records-content">
            <div class="records-description">
                <div class="records-name">
                    <h3 style="margin-block-end:0">
                        2019
                    </h3>
                    <h1 style="margin-block-start:0">
                        DASH
                    </h1>
                </div>
                <div class="records-cover">
                    <img src="<?php echo $dashCover; ?>">
                </div>
            </div>
            <div class="records-list">
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $dash1iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $dash2iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $dash3iframe; ?>"></div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="records-content">
            <div class="records-description">
                <div class="records-name">
                    <h3 style="margin-block-end:0">
                        2018
                    </h3>
                    <h1 style="margin-block-start:0">
                        Pretekst
                    </h1>
                </div>
                <div class="records-cover">
                    <img src="<?php echo $pretCover; ?>">
                </div>
            </div>
            <div class="records-list">
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $pret1iframe; ?>"></div>
                </div>
                <div class="records-list-item">
                    <div class="youtube-player" data-id="<?php echo $pret2iframe; ?>"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php

$str = ob_get_clean();

$page->show($str);