<?php

namespace App\Console\Commands;

use App\Models\Promotion;
use Illuminate\Console\Command;

class RemoveExpiredPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remover promociones vencidas';

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
        Promotion::whereDate('end_date', '<', date('Y-m-d'))
            ->where('status', Promotion::STATUS_ACTIVE)
            ->update(['status' => Promotion::STATUS_INACTIVE]);
    }
}
