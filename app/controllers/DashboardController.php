<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class DashboardController extends RedHotMayoWebController {
    public function __construct() {
        $this->beforeFilter('auth');
    }

    public function index() {
        $user = $this->getAuthedUser();

        // TODO: not the best place for this, at all... but the quick and easy fix...
        $valid = array('AmyEadmin', 'DonVadmin', 'dgiles', 'cgiles', 'jweyer');
        $uploadEnabled = (in_array($user->getUsername(), $valid)) ? true : false;

        return View::make('dashboard.index', ['canUpload' => $uploadEnabled]);
    }
}