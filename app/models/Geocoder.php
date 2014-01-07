<?php

interface Geocoder {

    /**
     * Takes in an address value object and calls an external API source in order to attempt a geocoding
     * the address into lat/lon values. If successful the addresses will have a valid lat/lon and true
     * will be returned. Otherwise, the addresses lat/lon will remain untouched and false will be returned
     *
     * @param Address $address
     * @return bool
     */
    public function geocode(Address $address);
} 