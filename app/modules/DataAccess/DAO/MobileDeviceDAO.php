<?php

interface MobileDeviceDAO {
    /**
     * Save a record and return the objectId
     *
     * @param MobileDevice $mobileDevice
     * @return mixed
     */
    public function save(MobileDevice $mobileDevice);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $mobileDevices
     * @return array
     */
    public function saveAll(array $mobileDevices);
}
