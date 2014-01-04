<?php

class AddressRepository implements Repository {
    protected $address;

    public function __construct(Address $address) {
        $this->address = $address;
    }

    public function all() {
        return Address::all();
    }

    public function find($id) {
        return Address::find($id);
    }

    public function create($input) {
        // TODO: Implement create() method.
    }
}