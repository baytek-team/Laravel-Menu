<?php

namespace Baytek\Laravel\Menu\Policies;

use Baytek\Laravel\Content\Policies\GeneralPolicy;
use Baytek\Laravel\Menu\Models\Menu;
use Baytek\Laravel\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy extends GeneralPolicy
{
    public $contentType = 'Menu';
}
