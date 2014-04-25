<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use redhotmayo\distribution\AccountSubscriptionManager;

class SessionsController extends RedHotMayoWebController {

    /** @var \redhotmayo\distribution\AccountSubscriptionManager $subscriptionManager */
    private $subscriptionManager;

    public function __construct(AccountSubscriptionManager $subscriptionManager) {
        $this->subscriptionManager = $subscriptionManager;
    }

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
            //TODO: set user's last logon to NOW()

            // if the user just came from subscription process, subscribe them to any leads
            $user = $this->getAuthedUser();
            $this->subscriptionManager->processNewUsersData($user);

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
     * @internal param int $id
     * @return Response
     */
    public function destroy() {
        Auth::logout();
        return Redirect::home();
    }

}
