<?php

namespace Baytek\Laravel\Menu\Models;

use Baytek\Laravel\Content\Models\Content;

class MenuItem extends Content
{
	/**
	 * Meta keys that the content expects to save
	 * @var Array
	 */
	protected $meta = [
		'author_id'
	];

	/**
	 * Content keys that will be saved to the relation tables
	 * @var Array
	 */
	public $relationships = [
		'content-type' => 'menu-item'
	];

	public function getRouteKeyName()
	{
	    return 'key';
	}
}
