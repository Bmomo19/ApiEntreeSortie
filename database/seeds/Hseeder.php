<?php

use App\Historique;
use Illuminate\Database\Seeder;

class Hseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Historique::class, 50)->create();
    }
}
