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
        $array['results'] = 'success';
        $array['data'] = $this->accountRepo->find($searchType, Input::all());
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

//    private function php2js($a) {
//        if (is_null($a)) {
//            return 'null';
//        }
//        if ($a === false) {
//            return 'false';
//        }
//        if ($a === true) {
//            return 'true';
//        }
//        if (is_scalar($a)) {
//            $a = addslashes($a);
//            $a = str_replace(" ", ' ', $a);
//            $a = str_replace(" ", ' ', $a);
//            $a = preg_replace('{(</)(script)}i', "$1'+'$2", $a);
//            return "'$a'";
//        }
//        $isList = true;
//        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
//            if (key($a) !== $i) {
//                $isList = false;
//                break;
//            }
//        $result = array();
//        if ($isList) {
//            foreach ($a as $v) $result[] = $this->php2js($v);
//            return '[ ' . join(', ', $result) . ' ]';
//        } else {
//            foreach ($a as $k => $v)
//                $result[] = $this->php2js($k) . ': ' . $this->php2js($v);
//            return '{ ' . join(', ', $result) . ' }';
//        }
//    }

}
