<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Illuminate\Console\Command;

class RemoveExpiredOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remover ofertas vencidas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Offer::whereDate('end', '<', date('Y-m-d'))
            ->where('status', Offer::ACTIVE)
            ->update(['status' => Offer::INACTIVE]);
    }
}
