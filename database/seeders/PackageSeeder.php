<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $packages = [
            [
                'name' => 'Basic',
                'price' => 1000,
                'duration_type' => 'daily',
                'duration' => '3',
                'description' => 'Basic Package!',
                'is_free' => true,
            ],
             [
                'name' => 'Standard',
                'price' => 2000,
                'duration_type' => 'weekly',
                'duration' => '1',
                'description' => 'Standard Package!',
                'is_free' => false,
            ],
             [
                'name' => 'Premium',
                'price' => 6500,
                'duration_type' => 'monthly',
                'duration' => '1',
                'description' => 'Basic Package!',
                'is_free' => false,
            ],
        ];

        foreach($packages as $package){
            Package::create($package);
        }
    }
}
