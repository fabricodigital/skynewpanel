<?php

namespace App\Libraries\Menu;

use Illuminate\Support\Facades\Auth;

class Menu
{
    /**
     * @var string
     */
    private $config;

    /**
     * @var string
     */
    private $activeIcon;

    /**
     * Menu constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->buildItems($this->config['sections']);
    }

    /**
     * @param $config
     * @return array
     */
    private function buildItems($config)
    {
        $items = [];

        foreach ($config as $section) {
            if (!$this->hasPermission($section)) {
                continue;
            }

            $item = new MenuItem();
            if (!empty($section['type'])) {
                $item->setType($section['type']);
            }
            if (!empty($section['title'])) {
                $item->setTitle(eval('return ' . $section['title'] . ';'));
            }
            if (!empty($section['icon'])) {
                $item->setIcon($section['icon']);
            }
            if (!empty($section['route'])) {
                $item->setRoute($section['route']);
            }
            if (!empty($section['append_html'])) {
                $item->setAppendHtml(eval('return ' . $section['append_html'] . ';'));
            }
            if (!empty($section['partial_file'])) {
                $item->setPartialFile($section['partial_file']);
            }

            $item->setActive($this->isItemActive($section));

            if (!empty($section['sections'])) {
                $item->setSubMenu($this->buildItems($section['sections']));
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @param $section
     * @return bool
     */
    private function isItemActive($section)
    {
        $isActive = false;

        if (!empty($section['sections'])) {
            foreach ($section['sections'] as $sec) {
                if ($this->isItemActive($sec)) {
                    $isActive = true;
                    break;
                }
            }
        } else if (
            !empty($section['uri_segments'])
            && in_array(request()->segment($this->config['uri_segment']), $section['uri_segments'])
        ) {
            $isActive = true;
        }

        return $isActive;
    }

    /**
     * @param $section
     * @return bool
     */
    private function hasPermission($section)
    {
        $hasPermission = true;

        if (!empty($section['sections'])) {
            $hasPermission = false;
            foreach ($section['sections'] as $sec) {
                if ($this->hasPermission($sec)) {
                    $hasPermission = true;
                    break;
                }
            }
        } else if (
            !empty($section['permission_class'])
            && !Auth::user()->can('view_index', $section['permission_class'])
        ) {
            $hasPermission = false;
        }

        return $hasPermission;
    }

    /**
     * @return string
     */
    public function getActiveIcon()
    {
        if (empty($this->activeIcon)) {
            foreach ($this->config['sections'] as $section) {
                $this->activeIcon = $this->activeIcon($section);
                if (!empty($this->activeIcon)) {
                    break;
                }
            }
        }

        return $this->activeIcon;
    }

    /**
     * @param $section
     * @return string
     */
    private function activeIcon($section)
    {
        $icon = '';

        if (!empty($section['sections'])) {
            foreach ($section['sections'] as $sec) {
                if ($this->activeIcon($sec) != '') {
                    $icon = $this->activeIcon($sec);
                    break;
                }
            }
        } else if (
            !empty($section['uri_segments'])
            && in_array(request()->segment($this->config['uri_segment']), $section['uri_segments'])
            && !empty($section['icon'])
        ) {
            $icon = $section['icon'];
        }

        return $icon;
    }
}
