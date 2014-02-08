<?php namespace redhotmayo\api\controllers;

use BaseController;
use Illuminate\Support\Facades\Input;
use redhotmayo\facade\Registration;
use redhotmayo\registration\exceptions\UserExistsException;
use redhotmayo\validation\ValidationException;

class ApiRegistrationController extends BaseController {
    const STORE = 'redhotmayo\api\controllers\ApiRegistrationController@store';

    public function store() {
        $input = json_decode(Input::get(0), true);
        try {
            $status = Registration::mobile($input);
            $results['status'] = $status;
        } catch (ValidationException $validationException) {
            $results['message'] = $validationException->getMessage();
        } catch (UserExistsException $userExistsException) {
            $results['message'] = $userExistsException->getMessage();
        }

        return $results;
    }

}