<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ExchangeRateService;
use App\Enums\Currency;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $exchangeRate = $this->getExchangeRate();

        return view('products.list', compact('products', 'exchangeRate'));
    }

    // 1. added $id param
    // 2. replaced find with findOrFail for better error handling
    public function show($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $exchangeRate = $this->getExchangeRate();

        return view('products.show', compact('product', 'exchangeRate'));
    }

    /**
     * @return float
     */
    private function getExchangeRate()
    {
        return (new ExchangeRateService)->getRate(Currency::USD, Currency::EURO);
    }
}
