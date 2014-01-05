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
                'userId' => 41,
                'zipCode' => 10002,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
//            [
//                'userId' => 16,
//                'zipCode' => 32210,
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ],
//            [
//                'userId' => 17,
//                'zipCode' => 29928,
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ],
//            [
//                'userId' => 18,
//                'zipCode' => 1940,
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ],
//            [
//                'userId' => 18,
//                'zipCode' => 49503,
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ],
//            [
//                'userId' => 18,
//                'zipCode' => 10002,
//                'created_at' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//            ],
        ];
        DB::table('subscriptions')->insert($subscriptions);
    }
} 