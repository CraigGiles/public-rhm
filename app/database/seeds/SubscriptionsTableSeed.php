<?php
/**
 * Created by PhpStorm.
 * User: gilesc
 * Date: 1/4/14
 * Time: 4:44 PM
 */

class SubscriptionsTableSeeder extends Seeder {
    public function run() {
//        DB::table('subscriptions')->truncate();
        DB::table('subscriptions')->delete();

        $subscriptions = [
            [
                'userId' => 1,
                'zipCode' => 94501,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'userId' => 1,
                'zipCode' => 91423,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'userId' => 1,
                'zipCode' => 92262,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        DB::table('subscriptions')->insert($subscriptions);
    }
} 