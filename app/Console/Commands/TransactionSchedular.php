<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TransactionSchedular extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:transaction-schedular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hit Transactions API after every one hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = app('App\Http\Controllers\Transactions')->transactions_new();
        dd($response);
        $this->info('Transactions function executed successfully.');
    }
}
