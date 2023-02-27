<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call( LocationSeeder::class );

        \App\Models\Quiz::factory(1)->create();

        \App\Models\User::factory()->create([
            'name' => 'Bob Doe',
            'email' => 'bob@doe.de',
            'password' => '$2y$10$NfeLh6KO6oMh9BAoatz4r.j6axa3k6nJDjzpgsdkIKNZvhwQDuT12'
        ]);
    }
}
