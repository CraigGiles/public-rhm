<?php

interface Repository {
    public function all();

    public function find($id);

    public function create($input);

    public function save($object);

    public function saveAll($objects);

}