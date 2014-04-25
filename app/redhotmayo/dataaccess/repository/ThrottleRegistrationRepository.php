<?php namespace redhotmayo\dataaccess\repository;


interface ThrottleRegistrationRepository {
    /**
     * Add a key used to register with the max number of times it can be used.
     *
     * @param $key
     * @param $max
     */
    public function addKey($key, $max);

    /**
     * Determines if a key is valid and the current number of registrations is under the max
     *
     * @param array $input
     * @return mixed
     */
    public function canUserRegister(array $input);

    /**
     * Decrements the max number of people who can register by one for the given key
     *
     * @param array $input
     */
    public function decrementMax(array $input);
} 
