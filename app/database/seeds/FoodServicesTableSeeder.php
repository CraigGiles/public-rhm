<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodServicesTableSeeder extends Seeder {

    public function run() {
        DB::table('food_services')
          ->delete();
        DB::statement('ALTER TABLE food_services AUTO_INCREMENT = 0;');

        $services = [
            [ 's2' => 'Bistro', 'service' => 'Casual Dining', 'meta' => 'Full Service, FSR, Bistro' ],
            [ 's2' => 'Casual Dining', 'service' => 'Casual Dining', 'meta' => 'Full Service, FSR' ],
            [ 's2' => 'Casual/Family', 'service' => 'Casual Dining', 'meta' => 'Family, Full Service, FSR' ],
            [ 's2' => 'Family Style', 'service' => 'Casual Dining', 'meta' => 'Family, Full Service, FSR' ],
            [ 's2' => 'FSR', 'service' => 'Casual Dining', 'meta' => 'Full Service, FSR' ],
            [ 's2' => 'Full Service', 'service' => 'Casual Dining', 'meta' => 'Full Service, FSR' ],
            [ 's2' => 'Fine Dining', 'service' => 'Fine Dining', 'meta' => 'Family, Full Service, FSR, Upscale, White Tablecloth' ],
            [ 's2' => 'Upscale Dining', 'service' => 'Fine Dining', 'meta' => 'Family, Full Service, FSR, Upscale, White Tablecloth' ],
            [ 's2' => 'White Tablecloth', 'service' => 'Fine Dining', 'meta' => 'Family, Full Service, FSR, Upscale, White Tablecloth' ],
            [ 's2' => 'Buffet', 'service' => 'Buffet', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'Café', 'service' => 'Fast Casual', 'meta' => 'Quick Service, QSR, LSR, Café' ],
            [ 's2' => 'Fast Casual', 'service' => 'Fast Casual', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'Fast Food', 'service' => 'Quick Service', 'meta' => 'Quick Service, QSR, LSR, Fast Food' ],
            [ 's2' => 'Limited Service', 'service' => 'Quick Service', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'LSR', 'service' => 'Quick Service', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'QSR', 'service' => 'Quick Service', 'meta' => 'Quick Service, LSR' ],
            [ 's2' => 'Quick Serve', 'service' => 'Quick Service', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'Quick Service', 'service' => 'Quick Service', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'Mostly take-out', 'service' => 'Take-Out', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'Take-out only', 'service' => 'Take-Out', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'Take-out/Delivery', 'service' => 'Take-Out', 'meta' => 'Quick Service, QSR, LSR' ],
            [ 's2' => 'EMPTY', 'service' => 'Choose Option', 'meta' => '' ],
            [ 's2' => 'N/A', 'service' => 'Choose Option', 'meta' => '' ],
        ];

        DB::table('food_services')
          ->insert($services);
    }
}
