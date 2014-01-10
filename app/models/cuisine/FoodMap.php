<?php

class FoodMap {
    public function getMap() {
        //read it in from config?
        return array(
            'MEXICAN' => 'MEXICAN',
            'N/A' => 'OTHER',
            'KOREAN' => 'ASIAN',
            'BBQ' => 'AMERICAN',
            'CHICKEN' => 'AMERICAN',
            'CAJUN' => 'AMERICAN'
        );
    }
}