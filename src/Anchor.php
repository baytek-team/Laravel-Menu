<?php

namespace Baytek\Laravel\Menu;

use Exception;

class Anchor extends Item
{
	protected $action = 'url';
	protected $append;
	protected $class = '';
	protected $confirm;
	protected $form;
	protected $location = '';
	protected $method = 'get';
	protected $model = '';
	protected $prepend;
	protected $text;
	protected $type = 'url';
}