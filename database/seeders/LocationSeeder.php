<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'name' => 'Campus Dresden',
            'website' => 'http://campus-dresden.de/',
            'phone' => '0351 79213897',
            'address' => 'Hübnerstraße 13, 01069 Dresden'
        ]);
    }
}
