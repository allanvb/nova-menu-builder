<?php

namespace Aiman\MenuBuilder\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Str;

class Menu extends Model
{
    /**
     * @var string
     */
    protected $defaultParentTag = 'ul';
    protected $withChildren;
    protected $withActive;
    protected $with = ['parentItems'];
    /**
     * @var string
     */
    protected $defaultChildTag = 'li';

    /**
     * Boot
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItems::class);
    }

    /**
     * @return HasMany
     */
    public function parentItems(): HasMany
    {
        return $this->hasMany(MenuItems::class)->whereNull('parent_id')
            ->orderby('parent_id')
            ->orderby('order')
            ->orderby('name');
    }

    /**
     * Return menu items
     *
     * @return  Collection
     */
    public function optionsMenu(): Collection
    {
        return $this->parentItems()
        ->orderby('parent_id')
            ->orderby('order')
            ->orderby('name')
            ->get();
    }

    /**
     * Return enabled menu items
     *
     * @return  Collection
     */
    public function optionsMenuEnabled(): Collection
    {
        return $this->parentItems;
    }

    /**
     * Render current menu items
     *
     * @param string $parentTag
     * @param string $childTag
     * @param string $parentClass
     * @param string $childClass
     *
     * @param bool $withChildren
     * @param bool $withActive
     * @return  string
     */
    public function render($parentTag = null, $childTag = null, $parentClass = null, $childClass = null, $withChildren = true, $withActive = true): string
    {
        $this->defaultParentTag = $parentTag ?? $this->defaultParentTag;
        $this->defaultChildTag = $childTag ?? $this->defaultChildTag;
        $this->parentClass = $parentClass;
        $this->withChildren = $withChildren;
        $this->withActive = $withActive;
        $this->childClass = $childClass;

        $content = $this->renderItem($this->optionsMenuEnabled());

        return $this->parentHtmlBuilder($content);
    }

    /**
     * Render html for each item
     *
     * @param   collection $items
     *
     * @return  string
     */
    private function renderItem($items): string
    {
        $menu = '';
        $current_route = Route::currentRouteName();
        foreach ($items as $item) {

            if (!$item->enabled) continue;

            $content = $item->html();

            $active = false;
            if($item->route)
            {
                if ($current_route == $item->route) $active = true;
                if($item->children->contains('route',$current_route)) $active = true;
            }


            $menu .= $this->buildTag($this->defaultChildTag, $item->classes, $active)
                . $content;

            if ($item->children->count() > 0 && $this->withChildren) {
                $childrenContent = $this->renderItem($item->children, $active);

                $menu .= $this->buildTag($this->defaultParentTag, $this->childClass)
                    . $childrenContent
                    . $this->closeTag($this->defaultParentTag);
            }

            $menu .= $this->closeTag($this->defaultChildTag);
        }

        return $menu;
    }

    /**
     * Generate html tags for parents
     *
     * @param string $content
     *
     * @param null $childClass
     * @return  string
     */
    private function parentHtmlBuilder($content, $childClass = null): string
    {
        return $this->buildTag($this->defaultParentTag, $childClass ?? $this->parentClass)
            . $content
            . $this->closeTag($this->defaultParentTag);
    }

    /**
     * Create html open tag for given tag
     *
     * @param string $tag
     * @param string | null $class
     *
     * @param bool $active
     * @return  string
     */
    private function buildTag($tag, $class = null, $active = false): string
    {
        $activeClass = $active ? 'active' : '';
        return "<{$tag} class='{$activeClass} {$class}'>";
    }

    /**
     * Close html tag
     *
     * @param   string $tag
     *
     * @return  string
     */
    private function closeTag($tag): string
    {
        return "</{$tag}>";
    }
}
