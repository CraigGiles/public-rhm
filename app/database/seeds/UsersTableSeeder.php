<?php


use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0;');

//        DB::table('users')->truncate();

        $users = [
            [
                'username' => 'testuser01',
                'password' => Hash::make('$Test123'),
                'email' => 'testuser01@somewhere.com',
                'emailVerified' => intval(true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'testuser02',
                'password' => Hash::make('$Test123'),
                'email' => 'testuser02@somewhere.com',
                'emailVerified' => intval(true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'testuser03',
                'password' => Hash::make('$Test123'),
                'email' => 'testuser03@somewhere.com',
                'emailVerified' => intval(true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'testuser04',
                'password' => Hash::make('$Test123'),
                'email' => 'testuser04@somewhere.com',
                'emailVerified' => intval(true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'testuser05',
                'password' => Hash::make('$Test123'),
                'email' => 'testuser05@somewhere.com',
                'emailVerified' => intval(true),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('users')->insert($users);

    }

    public function down() {
        DB::table('roles')
          ->delete();
    }
} 