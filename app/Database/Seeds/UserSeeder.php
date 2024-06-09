<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user_data = [];
        for ($i=1; $i <= 5 ; $i++) { 
            $user_data['username'] = 'testUser' . $i;
            $user_data['password'] = password_hash('12345678', PASSWORD_BCRYPT);
            // To insert the records of the current loop in the database as a one row 
            $this->db->table('users')->insert($user_data);
        }
    }
}
