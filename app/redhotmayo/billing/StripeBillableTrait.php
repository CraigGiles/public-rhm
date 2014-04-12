<?php  namespace redhotmayo\billing; 

use Laravel\Cashier\BillableTrait;

trait StripeBillableTrait {
    use BillableTrait;

    public function save() {
    }
} 
