<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiltersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('filters')->insert([
            [
                'name' => "Damas",
                'name_english' => "Ladies"
            ],
            [
                'name' => "Caballeros",
                'name_english' => "Gentlemen"
            ],
            [
                'name' => "Niños",
                'name_english' => "Children"
            ],
            [
                'name' => "Ramses",
                'name_english' => "Ramses"
            ]
        ]);
    }
}
