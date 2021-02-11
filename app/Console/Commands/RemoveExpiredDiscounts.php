<?php

namespace App\Console\Commands;

use App\Models\Discount;
use Illuminate\Console\Command;

class RemoveExpiredDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discounts:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remover descuentos vencidas';

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
        Discount::whereDate('end', '<', date('Y-m-d'))
            ->where('status', Discount::ACTIVE)
            ->update(['status' => Discount::INACTIVE]);
    }
}
