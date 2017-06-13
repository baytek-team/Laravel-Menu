<?php

namespace Baytek\Laravel\Menu;

use Exception;
use Request;

class Item
{
	use NodeTrait;

	protected $id;
	// protected $action = 'url';
	protected $uniqid;         // Unique ID of the form used
	protected $append;         // Content to append within the Anchor
	// protected $class;          // Class string or array
	protected $confirm;        // Confirmation text
	protected $form;           // Internal link form generated when link method not GET
	protected $location = '';  // Location of the link
	protected $method = 'get'; // Method of the link
	protected $model = null;   // Model used when generating urls to specific content
	protected $prepend;        // Piece of content to prepend within the Anchor
	protected $text;           // Text of the actual link
	protected $type = 'url';   // Type of URL to generate URL, Route, Action

	/**
	 * Create a new menu item instance
	 * @param string $text       Menu item text
	 * @param array  $properties List of properties set to object
	 */
	public function __construct($text = '', array $properties = [])
	{
		$this->wrapper = config('menu.item.wrapper', 'div');
        $this->class = config('menu.item.class', '');
        $this->prepend = config('menu.item.prepend', '');
        $this->append = config('menu.item.append', '');
        $this->before = config('menu.item.before', '');
        $this->after = config('menu.item.after', '');

		$this->text = $text;
		$this->uniqid = uniqid('frm_');

		// If no properties are set, assume this is an on page anchor
		if(empty($properties)) {
			$this->location = '#' . str_slug($text);
		}

		collect($properties)->each(function ($value, $property) {
			if(!property_exists($this, $property)) {
				throw new Exception("Setting invalid property '$property'");
			}

			// This could be better using magic setter
			$this->$property = $value;
		});

		if(strtolower($this->method) != 'get') {
			$this->form = sprintf('<form id="%1$s" action="%2$s" method="POST" style="display: none;">%3$s</form>',
			    $this->uniqid,
			    call_user_func($this->type, $this->location, $this->model),
			    csrf_field() . method_field(strtoupper($this->method)) ?: ''
			);
		}
	}

	/**
     * To string magic method that renders all properties into an HTML link
     * @return string HTML markup
     */
	public function __toString()
	{
		$isConfirmation = false;

		try{
			if(strtolower($this->method) == 'delete' || !empty($this->confirm)) {
			    $isConfirmation = true;
			    $this->class .= ' confirm';
			    $confirm = $this->confirm ?: 'Are you sure?';
			}

			$result = sprintf('<a%1$s href="%2$s"%3$s%4$s>%5$s</a>%6$s',
			    " class=\"{$this->getClasses()}\"",
			    $this->getLocation(),
			    (strtolower($this->method) == 'get') ? null : " data-form-id='$this->uniqid'",
			    ($isConfirmation ? " data-confirm=\"{$confirm}\"" : ""),
			    $this->prepend . $this->text . $this->append,
			    $this->form
			);
		}
		catch(Exception $e){
			dd($e);
		}

		return $result;
	}

	// public static function anchor($text, array $properties = []) {
	// 	return new static($text, $properties);
	// }

	/**
	 * Checks to see if the current location matches the link location
	 * @return boolean Active link or not
	 */
	public function isActive()
	{
		if(method_exists($this, 'getLocation') && $this->getLocation() == Request::url()) {
			return true;
		}

		return false;
	}

	public function getLocation()
	{
		return starts_with($this->location, '#') ?
			$this->location :
			call_user_func_array($this->type, array_filter([
				$this->location,
				$this->model
			])
		);
	}

	/**
	 * [link description]
	 * @param  [type] $link [description]
	 * @return [type]       [description]
	 */
	public static function goesTo($link, $model = null)
	{
	    if(is_string($link)) {
	        return $link;
	    }

	    $redirection = collect($link);
	    $keys = $redirection->keys();

	    // This checks to see if any of the array keys exist: 'action', 'route' and 'url'
	    if (!$keys->intersect(['action', 'route', 'url'])->count()) {
	        throw new Exception('The url is malformed, unable to create a link of type: ' . $keys->implode(', '));
	    }

	    // If we have one of those keys, return the helper function with the value
	    return call_user_func($redirection->keys()->first(), $redirection->first(), $model);
	}


	// public function getClasses()
	// {
	// 	$classes = $this->class;

	// 	if(is_string($classes)) {
	// 		$classes = explode(' ', $classes);
	// 	}

	// 	if($this->getLocation() == Request::url()) {
	// 		array_push($classes, 'active');
	// 	}

	// 	return implode(' ', array_filter($classes));
	// }

}
