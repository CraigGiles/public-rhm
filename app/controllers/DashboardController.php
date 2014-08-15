<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class DashboardController extends BaseController {
    public function __construct() {
        $this->beforeFilter('auth');
    }

    public function index() {
        return View::make('dashboard.index');
    }
} 