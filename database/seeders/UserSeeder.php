<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{User, Role};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $user =  User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make(123456),
        ]);
        $role = Role::find(1);

        $user->roles()->attach($role->id);
        User::factory()->count(10)->create();
    }
}
