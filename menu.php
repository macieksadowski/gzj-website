<?php

//require_once '/page.php';

class menu
{
    private $links;
    private $menuItems;
    private $current;
    private $menu;

    public function __construct($current, $menuItems)
    {
        $this->links = json_decode(file_get_contents(__DIR__.'/data/links.json'), true);
        $this->menuItems = json_decode(file_get_contents($menuItems), true);
        $this->current = $current;
        $this->menu = file_get_contents(__DIR__.'/templates/menu.html');
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getItems()
    {
        return $this->menuItems;
    }

    public function print($showContactBar = false, $showSocialBar = false)
    {
        page::fillWithData($this->menu, ['START' => $this->menuItems['Start']]);

        $str = '';
        $i = 1;
        foreach ($this->menuItems as $name => $link) {
            if ($name == $this->current) {
                $str .= '<li class="current" itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"> <span itemprop="name">'.$name.'</span> <meta itemprop="position" content="'.$i.'" /></li>';
            } else {
                $str .= '<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="'.$link.'"> <span itemprop="name">'.$name.'</span></a> <meta itemprop="position" content="'.$i.'" /></li>';
            }
            ++$i;
        }
        page::fillWithData($this->menu, ['MENUITEMS' => $str]);

        $this->socialBar($showSocialBar);

        $this->contactBar($showContactBar);

        return $this->menu;
    }

    private function contactBar($show)
    {
        $template = '';
        if ($show) {
            $template = file_get_contents(__DIR__.'/templates/contactBar.html');
            page::fillWithData($template, $this->links);
        }
        page::fillWithData($this->menu, ['CONTACTBAR' => $template]);
    }

    private function socialBar($show)
    {
        $template = '';
        if ($show) {
            $template = file_get_contents(__DIR__.'/templates/socialBar.html');
            page::fillWithData($template, $this->links);
        }
        page::fillWithData($this->menu, ['SOCIALBAR' => $template]);
    }
}