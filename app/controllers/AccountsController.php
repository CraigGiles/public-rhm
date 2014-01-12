<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
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
        dd($params);

        return 'Index accounts page';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return 'create new account';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        dd(Input::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id) {
        $params = Input::all();
        dd($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        //
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
        return "Upload excel spreadsheet";
    }

}
