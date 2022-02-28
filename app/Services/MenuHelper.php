<?php

namespace App\Services;


class MenuHelper
{


    public static function ol($menuItems, $currentPage, $class = 'menu')
    {

        $subMenuCounter = 0;

        $str = '<ol class="' . $class . '" itemscope itemtype="https://schema.org/BreadcrumbList">';
        $index = 0;

        foreach ($menuItems as $menuItem => $menuItemPath) {

            $current = ('/' . $currentPage == $menuItemPath);
            $subMenu = (is_array($menuItemPath));

            $subMenuItems = $subMenu ? $menuItemPath : null;

            $class = $current ? 'current ' : '';
            $class .= $subMenu ? 'sub-menu-parent' : '';

            $menuItemPath = ($current || $subMenu) ? '#' : $menuItemPath;

            $str .= '<li itemprop="itemListElement" itemscope  itemtype="https://schema.org/ListItem" class="' . $class . '">';
            $str .= $subMenu ? '<div>' : '';
            $str .= '<a  itemprop="item" href="' . $menuItemPath . '">';

            if ($subMenu) {

                $subMenuCounter++;

                $str .= '<label class="drop-icon" for="sm' . $subMenuCounter . '">' . $menuItem . ' ▾</label>';
            } else {
                $str .= '<span itemprop="name">' . $menuItem . '</span>';
            }

            $str .= '</a>';
            $str .= $subMenu ? '</div>' : '';
            if ($subMenu) {
                $str .= '<input type="checkbox" id="sm' . $subMenuCounter . '" class="sub-menu-toggle">';
                $str .= MenuHelper::ol($subMenuItems, $currentPage, 'sub-menu');
            }

            $str .= '<meta itemprop="position" content="' . $index . '" />';
            $str .= '</li>';

            $index++;
        }
        $str .= '</ol>';

        return $str;
    }
}
