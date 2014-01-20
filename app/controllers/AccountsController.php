<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use redhotmayo\dataaccess\repository\AccountRepository;

class AccountsController extends \BaseController {
    /**
     * @var redhotmayo\dataaccess\repository\AccountRepository
     */
    private $accountRepo;

    public function __construct(AccountRepository $accountRepo) {
        $this->accountRepo = $accountRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $params = Input::all();
        return $this->accountRepo->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        return 'store';
    }

    /**
     * Display the specified resource.
     *
     * @param  $searchType
     * @return Response
     */
    public function show($searchType) {
        $success = false;
        $array = array();

        try {
            $success = true;
            $array['data'] = $this->accountRepo->find($searchType, Input::all());
        } catch (Exception $e) {
            $success = false;
        }

        $array['status'] = $success;
        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        return "edit {$id}";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        return "update {$id}";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /**
     * Allows a user to upload an excel spreadsheet containing lead data
     *
     * @author Craig Giles <craig@gilesc.com>
     */
    public function upload() {
//        dd(Input::all());
        $file = Input::file('accounts');
        if (isset($file)) {
            $ext = $file->getClientOriginalExtension();
            $time = date('Ymdhms');
            $filename = public_path() . '/accounts/' . $time . '.' . $ext;
            $file->move($filename);

            //launch lead distribution process

        }

        return 'uploaded';
    }
}
