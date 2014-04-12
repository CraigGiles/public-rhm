<?php  namespace redhotmayo\billing; 

use Laravel\Cashier\BillableTrait;
use redhotmayo\dataaccess\repository\BillingRepository;

trait StripeBillableTrait {
    use BillableTrait;

//    /** @var \redhotmayo\dataaccess\repository\BillingRepository $billingRepository */
//    private $billingRepository;
//
//    function __construct(BillingRepository $billingRepository) {
//        $this->billingRepository = $billingRepository;
//    }

    public function save() {
//        if (get_parent_class($this) == 'Billing') {
//            $this->billingRepository->save($this);
//        }
    }
} 
