<?php namespace redhotmayo\rest;

class SearchParameters {
    private $search;
    private $constraints;

    public function __construct($search, $constraints) {
        $this->search = $search;
        $this->constraints = $constraints;
    }

    /**
     * @param mixed $constraints
     */
    public function setConstraints($constraints) {
        $this->constraints = $constraints;
    }

    /**
     * @return mixed
     */
    public function getConstraints() {
        return $this->constraints;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search) {
        $this->search = $search;
    }

    /**
     * @return mixed
     */
    public function getSearch() {
        return $this->search;
    }

} 