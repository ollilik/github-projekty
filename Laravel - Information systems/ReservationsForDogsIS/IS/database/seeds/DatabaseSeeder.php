<?php

/*
|-------------------------------------------------
| Autor: Nina Štefeková (xstefe11)
|-------------------------------------------------
|
*/

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
        $this->call(UserSeeder::class);
        $this->call(DogSeeder::class);
    }
}
