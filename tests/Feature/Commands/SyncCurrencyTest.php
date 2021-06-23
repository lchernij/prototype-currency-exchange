<?php

namespace Tests\Feature\Users;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncCurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function testSyncCurrency(): void
    {
        $mocksPath = base_path('tests/Feature/Commands/Mocks/SyncCurrency');

        Http::fake([
            'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/Moedas' => Http::response(file_get_contents($mocksPath . '/currencies.json'), 200, []),
        ]);
        
        $this->artisan('sync:currency')
            ->expectsOutput('Successfully sync, total of new currencies: 10');

        $allCurrencies = Currency::all();

        $this->assertEquals(10, count($allCurrencies));

        $this->assertEquals("AUD", $allCurrencies->first()->symbol);
        $this->assertEquals("Dólar australiano", $allCurrencies->first()->description);

        $this->assertNotNull($allCurrencies->first()->uuid);
    }
}
