<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\model\Note;

class ApiNoteController extends BaseController {
    private $accountRepo;

    public function __construct(AccountRepository $accounts) {
        $this->accountRepo = $accounts;
    }

    public function add() {
        $array = array();
        $success = false;
        try {
            $notes = array();
            $notesAsJson = Input::all();
            $notesAsJson = $notesAsJson[0];
            $stdObjects = json_decode($notesAsJson);
            $stdNotes = $stdObjects->notes;
            foreach ($stdNotes as $obj) {
                $notes[] = Note::FromStdClass($obj);
            }
            $this->accountRepo->attachNotesToAccount($notes);
            $success = true;
        } catch (Exception $e) {
            $array['message'] = $e->getMessage();
        }

        $array['status'] = $success;
        return $array;
    }

    public function search() {
        $array = array();

        try {
            $conditions = Input::all();
            $success = true;
            $array['data'] = $this->accountRepo->find($conditions);
        } catch (Exception $e) {
            $success = false;
            $array['message'] = $e->getMessage();
        }

        $array['status'] = $success;
        return $array;
    }

    public function delete() {
        dd(Input::all());
    }

    public function update() {
        dd(Input::all());
    }
} 