<?php

namespace Baytek\Laravel\Menu;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

use Blade;
use Exception;
use Route;

class Breadcrumbs
{
	protected $crumbs = [];

	protected $result = '';
	protected $path = '';


	public function __construct()
	{
		if (is_null(Route::getCurrentRoute())) return;

		$folders = explode('/', Route::getCurrentRoute()->uri());
		$path = '/';

		foreach($folders as $index => $folder) {
			$folder = str_replace('?', '', $folder);
			$name = title_case($folder);
			$parameters = Route::getCurrentRoute()->parameters();

			foreach($parameters as $key => $value) {
				if($folder == "{" . strtolower($key) . "}") {

					if(is_object($value) && ($value instanceof \App\User || $value instanceof \Baytek\Laravel\Users\User)) {
						$name = $value->name;
						$folder = $value->id;
					}
					else if(is_object($value) && $value instanceof Model) {
						$name = $value->title;
						$folder = $value->id;
					}
					else if(is_object($value) && $value instanceof Collection) {
						\Log::info('We are getting a collection of items for the breadcrumbs', [$key, $value]);
					}
					else if(class_exists('\Baytek\Laravel\Content\Models\Content')) {
						$content = \Baytek\Laravel\Content\Models\Content::find($value);
						if($content && !is_null($content->title)) {
							$name = $content->title;
							$folder = $value;
						}
						break;
					}
				}
			}

			$path .= $folder . '/';

			if(count($folders) != $index + 1) {
				$this->result .= new Anchor($name, [
					'location' => $path,
					'type' => 'url',
					'class' => 'section'
				]);

				$this->result .= "<div class='divider'> / </div>";
			}
			else {
				$this->result .= "<div class='active section'>" . ucfirst($name) . "</div>";
			}
		}
	}

	public function __toString()
	{
		return $this->result;
	}
}
