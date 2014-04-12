<?php

use Illuminate\Support\Facades\Input;
use redhotmayo\dataaccess\repository\BillingRepository;

class BillingController extends BaseController {
    /** @var BillingRepository $billingRepo */
    private $billingRepo;

    function __construct(BillingRepository $billingRepo) {
        $this->billingRepo = $billingRepo;
    }

    public function index() {
    }

    public function store() {
        dd(Input::all());
    }
}
