<?php

namespace App\Http\Controllers\Admin;

use TCG\Voyager\Http\Controllers\VoyagerAuthController as BaseVoyagerAuthController;

class VoyagerAuthController extends BaseVoyagerAuthController
{
    public function username()
    {
        return 'username';
    }
}
