<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrenciesCollectionResource;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy("symbol")->paginate(5);
        
        return new CurrenciesCollectionResource($currencies);
    }

    public function show(string $uuid)
    {
        $currency = Currency::where('uuid',$uuid)->firstOrFail();

        return CurrencyResource::make($currency);
    }
}
