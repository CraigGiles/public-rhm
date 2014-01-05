<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();

        $this->call('UsersTableSeeder');
        $this->command->info('User table seeded!');
//        $this->call('SubscriptionsTableSeeder');
//        $this->command->info('Subscriptions table seeded!');
    }

}