<?php

namespace App\Http\Controllers\User\Currency;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\CurrenciesCollectionResource;
use App\Http\Resources\User\CurrencyResource;
use App\Models\UserCurrency;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        $currencies = UserCurrency::with(['currency' => function ($q) {
            $q->orderBy('symbol');
        }])->User($this->user->id)->paginate(5);

        return new CurrenciesCollectionResource($currencies);
    }

    public function show(string $uuid)
    {
        $currency = UserCurrency::with('currency')->where('uuid', $uuid)->firstOrFail();

        return CurrencyResource::make($currency);
    }
}
