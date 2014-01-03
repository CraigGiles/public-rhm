<?php

interface Repository {
    public function all();

    public function find($id);

    public function create($input);

}