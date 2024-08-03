<?php

namespace App\Services;

use App\Models\Weather;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{

    protected $cityName;

    public function getWeatherCity(string $cityName)
    {
        $this->cityName = $cityName;

        $cacheData = $this->retrieveCache($cityName);

        if ($cacheData) {

            Log::info('retrieve form cache');

            return ['data' => $cacheData];
        }

        $data = $this->retrieveFromDB();

        if (!$data) {

            Log::info('retrieve form api');

            $this->makeApiCall();
        }


        $data = $this->retrieveFromDB();
        Log::info('retrieve form data base');

        return [
            'data' => $data
        ];
    }

    protected function makeApiCall(): void
    {
        try {

            $url = config('weather.url');

            $key = config('weather.key');

            $response = Http::get($url, [
                'q' => $this->cityName,
                'appid' => $key,
            ]);

            $params = [
                'city' => $this->cityName,
                'data' => $response->body(),
            ];

            if ($response->ok()) {

                $this->store($params);
            }
        } catch (Exception $exception) {

            Log::error('unable to make api call', ['stack' => $exception]);
        }
    }

    protected function store(array $params)
    {
        Weather::create($params);
    }

    protected function retrieveFromDB()
    {
        $record = Weather::where('city', $this->cityName)->first(['data']);

        if ($record) {

            $this->storeCache($this->cityName, json_encode($record['data']));

            return json_decode($record['data']);
        }

        return null;
    }

    private function storeCache(string $key, string $value): void
    {
        Cache::add($key, $value, 300);
    }
    private function retrieveCache(string $key)
    {
        $record = Cache::get($key);
        if ($record) {

            return json_decode(json_decode($record, true));
        }
        return $record;
    }
}
