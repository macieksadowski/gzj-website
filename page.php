<?php

require_once 'menu.php';

class page
{
    private $menu;
    private $page_info;

    public function __construct()
    {
        $this->page_info = json_decode(file_get_contents('./page.json'));

        $this->menu = new menu($this->page_info->PAGE_NAME, './menu.json', './links.json');
    }

    public function show($content)
    {
        echo '<!DOCTYPE HTML>
        <html lang="pl">';
        echo $this->header();
        echo '<body>
        <!-- This div is used as container for whole page-->
        <div class="page-container">';
        echo $this->menu->print(true, true);
        echo '<main>
		<!-- This is a container for main content of page. -->
		<div class="content">';

        echo file_get_contents($content);

        echo '</div>
		</main>';
        echo $this->footer();
        echo '	</div>
        </body>
        </html>';
    }

    private function footer()
    {
        $str = file_get_contents('./templates/footer.html');

        return str_replace('$date', date('Y'), $str);
    }

    private function header()
    {
        $str = file_get_contents('./templates/header.html');
        $str .= $this->title();
        $str .= $this->meta_name('description', $this->page_info->DESCRIPTION);
        $str .= $this->meta_name('keywords', $this->page_info->KEYWORDS);

        $str .= '<!-- 	Facebook meta  	-->'.PHP_EOL;
        $str .= '<meta property="og:title" content="'.$this->page_info->PAGE_NAME.' - Główny Zawór Jazzu" />'.PHP_EOL;
        foreach ($this->page_info->FACEBOOK_META as $key => $value) {
            $str .= $this->meta_property($key, $value);
        }
        $str .= '<!-- 	Google funcs	-->'.PHP_EOL;
        $str .= '<script type="application/ld+json">'.PHP_EOL;
        $str .= json_encode($this->page_info->GOOGLE_META, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL;
        $str .= '</script>'.PHP_EOL;
        foreach ($this->page_info->css as $value) {
            $str .= $this->link('stylesheet', $value, 'text/css');
        }

        return $str;
    }

    private function title()
    {
        return '<title>'.$this->page_info->PAGE_NAME.'</title>'.PHP_EOL;
    }

    private function meta_property($property, $content)
    {
        return '<meta property="'.$property.'" content="'.$content.'" />'.PHP_EOL;
    }

    private function meta_name($name, $content)
    {
        return '<meta name="'.$name.'" content="'.$content.'" />'.PHP_EOL;
    }

    private function link($rel, $href, $type = '')
    {
        return '<link rel="'.$rel.'" href="'.$href.'" type="'.$type.'" />'.PHP_EOL;
    }
}