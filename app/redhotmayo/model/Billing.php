<?php  namespace redhotmayo\model;


use redhotmayo\billing\StripeBillableTrait;

class Billing extends DataObject {
    use StripeBillableTrait;

    public function addBasicSubscription($token) {
        //do something
    }
}
