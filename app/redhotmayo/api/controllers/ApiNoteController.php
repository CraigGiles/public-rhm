<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\api\auth\ApiSession;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\model\Note;

class ApiNoteController extends BaseController {
    const ADD = 'redhotmayo\api\controllers\ApiNoteController@add';

    const R_DATA = 'data';
    const R_STATUS = 'status';
    const R_MESSAGE = 'message';

    private $accountRepo;

    public function __construct(AccountRepository $accounts) {
        $this->accountRepo = $accounts;
    }

    public function add() {
        $values = Input::json()->all();
        $array = array();
        $success = false;
        $notes = array();
        try {
            //get current user associated with the API token
            $session = new ApiSession();
            $id = $session->getIdOfAuthedUser($values['token']);

            if (isset($id)) {
                $notesArray = $values['notes'];

                foreach ($notesArray as $obj) {
                    $notes[] = Note::FromArray($obj);
                }

                $this->accountRepo->attachNotesToAccount($notes);
                $success = true;
            }

        } catch (Exception $e) {
            $array[self::R_MESSAGE] = $e->getMessage();
        }

        $array[self::R_STATUS] = $success;
        return $array;
    }

    public function search() {
        $array = array();

        try {
            $conditions = Input::all();
            $array[self::R_DATA] = $this->accountRepo->find($conditions);
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $array[self::R_MESSAGE] = $e->getMessage();
        }

        $array[self::R_STATUS] = $success;
        return $array;
    }

    public function delete() {
        dd(Input::all());
    }

    public function update() {
        dd(Input::all());
    }
} 