<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BCBService
{
    const BASE_URL = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata';
    const ROTES = [
        'Currency' => '/Moedas',
    ];

    public function doRequest($method, $uri, $params = [])
    {
        $url = self::BASE_URL . $uri;
        
        $response = Http::send($method, $url);
        
        return $response->json();
    }

    public function getAllCurrency()
    {
        return $this->doRequest('GET', self::ROTES['Currency']);
    }
}
