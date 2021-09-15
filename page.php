<?php

require_once 'defines.php';

require_once SITE_ROOT.'/menu.php';

define('DEFAULTLAYOUT', __DIR__.'/templates/defaultLayout.html');
define('SUBPAGELAYOUT', __DIR__.'/templates/subpageLayout.html');

class page
{
    public $menu;
    private $page_info;
    private $page;
    private $layout;

    public function __construct($menu = __DIR__.'/data/menu.json')
    {
        $this->page_info = $this->getData('./page.json');
        $this->setLayout(DEFAULTLAYOUT);
        $this->menu = new menu($this->page_info['PAGE_NAME'], $menu);
    }

    public function getPageInfo()
    {
        return $this->page_info;
    }

    public function setLayout($layout)
    {
        $this->page = file_get_contents($layout);
        $this->layout = $layout;
    }

    public function show($content)
    {
        $this->header();
        $this->footer();
        if (SUBPAGELAYOUT == $this->layout) {
            $this->toLayout('PAGE_NAME', $this->page_info['PAGE_NAME']);
        }
        $this->toLayout('MENU', $this->menu->print(true, true));
        $this->scripts();
        $this->toLayout('CONTENT', $content);
        $this->toLayout('MODAL', null);
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

    public function getPage()
    {
        return $this->page;
    }

    public function getTemplate($templateName)
    {
        return file_get_contents(SITE_ROOT.'/templates/'.$templateName.'.html');
    }

    protected function toLayout($field, $content)
    {
        $this->page = str_replace('{{'.$field.'}}', $content, $this->page);
    }

    protected function scripts()
    {
        $str = '';
        if (isset($this->page_info['SCRIPTS'])) {
            foreach ($this->page_info['SCRIPTS'] as $script) {
                $str .= '<script src="/script/'.$script.'.js" type="text/javascript"></script>'.PHP_EOL;
            }
            $this->fillWithData($this->page, ['SCRIPTS' => $str]);
        }
        $this->toLayout('SCRIPTS', $str);
    }

    protected function footer()
    {
        $data = ['year' => date('Y')];
        $footer = file_get_contents(__DIR__.'/templates/footer.html');
        $this->fillWithData($footer, $data);
        $this->toLayout('FOOTER', $footer);
    }

    protected function header()
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
        return '<title>'.$this->page_info['PAGE_NAME'].' | Główny Zawór Jazzu</title>'.PHP_EOL;
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