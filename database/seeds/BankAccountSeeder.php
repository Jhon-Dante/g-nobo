<?php

use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bank_accounts')->insert([
        	[
        		"name" => 'Infinity Wará C.A.',
        		"bank_id" => 6,
        		'number' => '12345678',
        		'type' => '1',
        		'identification' => '12345'
        	],
        	[
        		"name" => "Heberlyn's Corp C.A.",
        		"bank_id" => 7,
        		'number' => '12345674445568',
        		'type' => '1',
        		'identification' => '15462'
        	]
        ]);
    }
}
