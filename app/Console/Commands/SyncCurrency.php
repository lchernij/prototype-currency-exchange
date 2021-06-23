<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Services\BCBService;
use Illuminate\Console\Command;

class SyncCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the currency in database';

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
     * @return int
     */
    public function handle()
    {
        $BCDService = new BCBService();
        $newValues = 0;

        $allCurrentCurrencies = Currency::all();
        $allNewCurrencies = $BCDService->getAllCurrency()['value'];

        foreach ($allNewCurrencies as $newCurrency) {
            $currentCurrency = $allCurrentCurrencies->firstWhere('symbol', $newCurrency['simbolo']);

            if (empty($currentCurrency)) {
                Currency::create([
                    'symbol' => $newCurrency['simbolo'],
                    'description' => $newCurrency['nomeFormatado']
                ]);
                $newValues++;
            }
        }

        $this->info('Successfully sync, total of new currencies: ' . $newValues);
    }
}
