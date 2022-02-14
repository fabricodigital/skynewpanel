<?php

namespace App\Libraries\Menu;

class MenuItem
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string
     */
    private $appendHtml;

    /**
     * @var string
     */
    private $partialFile;

    /**
     * @var array
     */
    private $subMenu = [];

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string|null
     */
    public function getAppendHtml(): ?string
    {
        return $this->appendHtml;
    }

    /**
     * @param string $appendHtml
     */
    public function setAppendHtml(string $appendHtml): void
    {
        $this->appendHtml = $appendHtml;
    }

    /**
     * @return string|null
     */
    public function getPartialFile(): ?string
    {
        return $this->partialFile;
    }

    /**
     * @param string $partialFile
     */
    public function setPartialFile(string $partialFile): void
    {
        $this->partialFile = $partialFile;
    }

    /**
     * @return array
     */
    public function getSubMenu(): array
    {
        return $this->subMenu;
    }

    /**
     * @param array $subMenu
     */
    public function setSubMenu(array $subMenu): void
    {
        $this->subMenu = $subMenu;
    }
}
