<?php

namespace Baytek\Laravel\Menu;

trait NodeTrait
{
	protected $class = '';
	protected $attributes = [];

	public function getClasses()
	{
		$classes = $this->class;

		if(is_string($classes)) {
			$classes = explode(' ', $classes);
		}

		if($this->isActive()) {
			array_push($classes, 'active');
		}

		return implode(' ', array_filter($classes));
	}

	public function getAttributes()
	{
		$attributes = '';
		collect($this->attributes)->put('class', $this->getClasses())
			->each(function ($value, $attribute) use (&$attributes) {
				$attributes .= " $attribute=\"$value\"";
			});

		return $attributes;
	}
}
