<?php

namespace Baytek\Laravel\Menu\Exceptions;

class InvalidPropertyException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $property Name of invalid property
     */
    public function __construct($property)
    {
        $this->message = "Setting invalid property '$property'";
    }
}
