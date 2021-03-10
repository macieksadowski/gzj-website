<?php

class menu
{
    private $links;
    private $menuItems;
    private $current;

    public function __construct($current, $menuItems, $links)
    {
        $this->links = json_decode(file_get_contents($links), true);
        $this->menuItems = json_decode(file_get_contents($menuItems), true);
        $this->current = $current;
    }

    public function print($showContactBar = false, $showSocialbar = false)
    {
        $opening = '<header>
					<!-- This is a container for navbar-->
					<div class="header">';
        $menuBtn = file_get_contents('./templates/menuBtn.html');
        $menuItemsBegin = '
		<!-- Navigation list (subpages)-->
		<ol class="menu" itemscope itemtype="https://schema.org/BreadcrumbList">';
        $menuItemsClose = '</ol>';
        $closing = '</div>
					</header>';
        $str = $opening.$this->siteLogo().$menuBtn.$menuItemsBegin;

        $i = 1;
        foreach ($this->menuItems as $name => $link) {
            if ($name == $this->current) {
                $str .= '<li class="current" itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"> <span itemprop="name">'.$name.'</span> <meta itemprop="position" content="'.$i.'" /></li>';
            } else {
                $str .= '<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem"><a  itemprop="item" href="'.$link.'"> <span itemprop="name">'.$name.'</span></a> <meta itemprop="position" content="'.$i.'" /></li>';
            }
            ++$i;
        }
        $str .= $menuItemsClose;
        if ($showSocialbar) {
            $str .= $this->socialBar();
        }
        if ($showContactBar) {
            $str .= $this->contactBar();
        }
        $str .= $closing;

        return $str;
    }

    private function prepareHTML($source, $variablesToReplace)
    {
        $str = file_get_contents($source);
        $variablesToReplace = (is_array($variablesToReplace)) ? $variablesToReplace : [$variablesToReplace];
        foreach ($variablesToReplace as $name => $variable) {
            $toReplace = '$'.$name;
            $str = str_replace($toReplace, $variable, $str);
        }

        return $str;
    }

    private function siteLogo()
    {
        return $this->prepareHTML('./templates/siteLogo.html', $this->menuItems);
    }

    private function contactBar()
    {
        return $this->prepareHTML('./templates/contactBar.html', $this->links);
    }

    private function socialBar()
    {
        return $this->prepareHTML('./templates/socialBar.html', $this->links);
    }
}