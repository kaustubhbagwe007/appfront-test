<?php

namespace App\Services;

use App\Enums\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    private $url;

    public function __construct()
    {
        $this->url = config('services.exchange_rate.endpoint');
    }

    public function getRate(Currency $from, Currency $to)
    {
        $from = $from->value;
        $to = $to->value;
        $key = "exchange_rate_{$from}_{$to}";

        // cache api response
        return Cache::remember($key, now()->addMinutes(30), function() use($from, $to) {
            $response = Http::timeout(5)->get("{$this->url}/{$from}");

            if ($response->ok()) {
                $body = json_decode($response->body(), true);
                
                if (isset($body['rates'][$to])) {
                    return $body['rates'][$to];
                }
            }

            return config('services.exchange_rate.fallback_rate');
        });
    }
}