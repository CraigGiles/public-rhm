<?php


use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0;');

        $users = [
            [
                'username' => 'demouser01',
                'password' => Hash::make('$Test123'),
                'email' => 'demouser01@somewhere.com',
                'emailVerified' => intval(true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('users')->insert($users);

    }

    public function down() {
        DB::table('users')
          ->delete();
    }
} 