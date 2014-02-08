<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class SessionsController extends \BaseController {

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $input = Input::all();

        $attempt = Auth::attempt(array(
            'username' => $input['username'],
            'password' => $input['password']
        ));

        if ($attempt) {
            //set user's last logon to NOW()
            //redirect to intended page
            return Redirect::intended('/')
                ->with('flash_message', 'You have been logged in');
        } else {
            return Redirect::to('login')
                           ->with('flash_message', 'Login Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy() {
        Auth::logout();
        return Redirect::home();
    }

}
