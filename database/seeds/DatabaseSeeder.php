<?php

use App\Historique;
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
        //$this->call(Hseeder::class);
        $this->call(Useeder::class);
    }
}
