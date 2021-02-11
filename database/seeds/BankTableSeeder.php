<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->insert([
            [
                'name' => "Banesco"
            ],
            [
                'name' => "Banco de Venezuela"
            ],
            [
                'name' => "BBVA Provincial"
            ],
            [
                'name' => "Banco Mercantil"
            ],
            [
                'name' => "BOD"
            ],
            [
                'name' => "Banco Bicentenario del Pueblo"
            ],
            [
                'name' => "Banco Nacional de Crédito BNC"
            ],
            [
                'name' => "Banco del Tesoro"
            ],
            [
                'name' => "Bancaribe"
            ],
            [
                'name' => "Banco Exterior"
            ],
            [
                'name' => "BFC Banco Fondo Común"
            ],
            [
                'name' => "Banco Venezolano de Crédito"
            ],
            [
                'name' => "Banco Sofitasa"
            ],
            [
                'name' => "Banplus"
            ],
            [  
                'name' => "Bancrecer"
            ],
            [
                'name' => "Banco Plaza"
            ],
            [
                'name' => "Banco Caroní"
            ],
            [
                'name' => "Banco Activo"
            ],
            [
                'name' => "DELSUR"
            ],
            [
                'name' => "Banfanb"
            ],
            [
                'name' => "100% Banco"
            ],
            [
                'name' => "Banco Agrícola de Venezuela"
            ],
            [
                'name' => "Bancamiga"
            ],
            [
                'name' => "Banco de Comercio Exterior (Bancoex)"
            ],
            [
                'name' => "Citibank Sucursal Venezuela"
            ],
            [
                'name' => "Mi Banco"
            ],
            [
                'name' => "Bangente"
            ],
            [
                'name' => "Instituto Municipal de Crédito Popular (IMCP)"
            ],
            [
                'name' => "Banco de Exportación y Comercio"
            ],
            [
                'name' => "Banco Internacional de Desarrollo"
            ],
        ]);
    }
}
