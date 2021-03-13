<?php

require_once 'menu.php';

class page
{
    public $menu;
    private $page_info;
    private $page;

    public function __construct($menu = __DIR__.'/data/menu.json')
    {
        $this->page_info = $this->getData('./page.json');
        $this->page = file_get_contents(__DIR__.'/templates/layout.html');

        $this->menu = new menu($this->page_info['PAGE_NAME'], $menu);
    }

    public function getPageInfo()
    {
        return $this->page_info;
    }

    public function show($content)
    {
        $this->header();
        $this->footer();
        $this->toLayout('MENU', $this->menu->print(true, true));
        $this->scripts();
        $this->toLayout('CONTENT', $content);
        echo $this->page;
    }

    public static function getData($dataFile)
    {
        return json_decode(file_get_contents($dataFile), 1);
    }

    public static function fillWithData(&$source, $data)
    {
        foreach ($data as $key => $value) {
            $source = str_replace('{{'.$key.'}}', $value, $source);
        }
    }

    private function toLayout($field, $content)
    {
        $this->page = str_replace('{{'.$field.'}}', $content, $this->page);
    }

    private function scripts()
    {
        $str = '';
        if (isset($this->page_info['SCRIPTS'])) {
            foreach ($this->page_info['SCRIPTS'] as $script) {
                $str .= '<script src="../script/'.$script.'.js" type="text/javascript"></script>'.PHP_EOL;
            }
            $this->fillWithData($this->page, ['SCRIPTS' => $str]);
        }
        $this->toLayout('SCRIPTS', $str);
    }

    private function footer()
    {
        $data = ['year' => date('Y')];
        $footer = file_get_contents(__DIR__.'/templates/footer.html');
        $this->fillWithData($footer, $data);
        $this->toLayout('FOOTER', $footer);
    }

    private function header()
    {
        $template = file_get_contents(__DIR__.'/templates/header.html');

        $this->fillWithData(
            $template,
            ['PAGE_NAME' => $this->title(),
                'DESCRIPTION' => $this->meta('name', 'description', $this->page_info['DESCRIPTION']),
                'KEYWORDS' => $this->meta('name', 'keywords', $this->page_info['KEYWORDS']), ]
        );
        $this->toLayout('HEADER', $template);
    }

    private function title()
    {
        return '<title>'.$this->page_info['PAGE_NAME'].'</title>'.PHP_EOL;
    }

    private function meta($type, $value, $content)
    {
        if ('property' == $type) {
            $str = '<meta property="'.$value.'" content="'.$content.'" />'.PHP_EOL;
        }
        if ('name' == $type) {
            $str = '<meta name="'.$value.'" content="'.$content.'" />'.PHP_EOL;
        }
        if (isset($str)) {
            return $str;
        }
    }

    private function link($template, $rel, $href, $type = '')
    {
        return '<link rel="'.$rel.'" href="'.$href.'" type="'.$type.'" />'.PHP_EOL;
    }
}