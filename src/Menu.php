<?php

namespace Baytek\Laravel\Menu;

use Illuminate\Database\Eloquent\Collection;

use Request;

/*
    The menu class needs to accomplish the following.
    * Needs to be open, and easy to manipulate insert items at any index, remove items, reorder items
    * Menu's can have their own models and tables to be manage as data driven
    * Menu's should also have the ability to generate based on content structure
    *

    Sample:

    $menu = new Menu;
    (new Menu)->push($menu->button('test', [
        'action' => 'Controller@index'
    ]));
 */

class Menu extends Collection
{
    use NodeTrait;

    private $wrapper = 'div';
    private $prepend = '';
    private $append = '';
    private $before = '';
    private $after = '';

    function __construct(array $value = [], array $properties = [])
    {
        parent::__construct($value);

        $this->wrapper = config('menus.menu.wrapper', 'div');
        $this->class = config('menus.menu.class', '');
        $this->prepend = config('menus.menu.prepend', '');
        $this->append = config('menus.menu.append', '');
        $this->before = config('menus.menu.before', '');
        $this->after = config('menus.menu.after', '');

        // This is a sort of hack because the way the blade templates are setup.
        //
        // I would like to review this code and come up with a better solution.
        if(count($properties) == 1 && $properties[0]) {
            $properties = $properties[0];
        }

        collect($properties)->each(function ($value, $property)
        {
            if(!property_exists($this, $property)) {
                throw new Exception("Setting invalid property '$property'");
            }

            // This could be better using magic setter
            $this->$property = $value;
        });
    }

    /**
     * Create an anchor menu item
     * @param  string $text             Link text
     * @param  array  $properties       List of properties of the link
     * @return \Baytek\Menu\Item        Item Object
     */
    public static function anchor($text, array $properties = [])
    {
        return new Anchor($text, $properties);
    }

    /**
     * Create an button menu item
     * @param  string $text             Button text
     * @param  array  $properties       List of properties of the link
     * @return \Baytek\Menu\Item        Item Object
     */
    public static function button($text, array $properties = [])
    {
        return new Button($text, $properties);
    }

    /**
     * Create an link menu item
     * @param  string $text             Link text
     * @param  array  $properties       List of properties of the link
     * @return \Baytek\Menu\Item        Item Object
     */
    public static function link($text, array $properties = [])
    {
        return new Link($text, $properties);
    }

    /**
     * Render the collection of Menu Items to an HTML list
     * @return string            HTML markup list
     */
    public function toNav()
    {
        $menu = $this->map(function($link)
        {
            return (string)$link;
        })->implode('');

        return sprintf('%1$s<%2$s%3$s>%4$s%5$s%6$s</%2$s>%7$s',
            $this->prepend ?: null,
            $this->wrapper,
            $this->getAttributes(),
            $this->before ?: null,
            $menu,
            $this->after ?: null,
            $this->append ?: null
        );
        // $class = $this->class ? " class=\"$this->class\"" : '';
        // return "$this->prepend<$this->menuWrapper$class>$menu</$this->menuWrapper>$this->append";
    }

    /**
     * To string magic method that simply runs the toNav method
     * @return string HTML markup list
     */
    public function __toString()
    {
        return $this->toNav();
    }
}
