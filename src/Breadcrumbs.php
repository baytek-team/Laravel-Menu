<?php

namespace Baytek\Laravel\Menu;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

// We should try to avoid dependence on other modules
use Baytek\Laravel\Content\Models\Content;

use Blade;
use Exception;
use Route;

use Illuminate\Support\Str;

class Breadcrumbs
{
	protected $crumbs = [];

	protected $result = '';
	protected $path = '';


	public function __construct()
	{
	    if(is_null(Route::getCurrentRoute())) return;

	    $folders = explode('/', Route::getCurrentRoute()->uri());
	    $path = '/';

	    foreach($folders as $index => $folder) {
	        $name = Str::title($folder);

	        $parameters = Route::getCurrentRoute()->parameters();

	        foreach($parameters as $key => $value) {
	            if($folder == "{" . strtolower($key) . "}") {

	                if(is_object($value) && $value instanceof Model) {
	                	$name = $value->title;
	                	$folder = $value->id;

	                	//Handle users
	                	if ($value->name) {
	                		$name = $value->name;
	                	}
	                }
	                else if(is_object($value) && $value instanceof Collection) {
	                	dd($content);
	                }
	                else {
	                	$content = Content::find($value);
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
