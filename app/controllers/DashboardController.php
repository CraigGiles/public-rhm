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
        $valid = array('amye', 'donv', 'dgiles', 'cgiles', 'jweyer');

        $uploadEnabled = (in_array($user->getUsername(), $valid)) ? true : false;

        return View::make('dashboard.index', ['canUpload' => $uploadEnabled]);
    }
} 