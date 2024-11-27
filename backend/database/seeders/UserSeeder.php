<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        
        $users = [
            [
                'name' => 'Admin 1',
                'email' => 'admin1@gmail.com',
                'password' => 'password123',  
                'status' => 1,  
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@gmail.com',
                'password' => 'password123',  
                'status' => 1,  
            ],
            [
                'name' => 'Admin 3',
                'email' => 'admin3@gmail.com',
                'password' => 'password123',  
                'status' => 1,  
            ],
        ];

        foreach ($users as $user) {
            User::create($user);  
        }

        echo "3 usuários (Admin 1, 2 e 3) criados com sucesso!\n";
    }
}
