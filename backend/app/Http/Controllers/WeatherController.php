<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{

    public function getWeather(Request $request , WeatherService $weatherService){

        $cityName = $request->input('city');

        return $weatherService->getWeatherCity($cityName);

    }
}
