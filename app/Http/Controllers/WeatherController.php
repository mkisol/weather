<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
   public function getWeather(Request $request)
    {
        $city = $request->input('city');
        if($city == '')
        {
            $city = 'Berlin';
        }
        $client = new Client();
        $apiKey = '2ee7a058d34140b0baf60540233105&q'; // Replace with your OpenWeatherMap API key

        $response = $client->get("http://api.weatherapi.com/v1/current.json?key={$apiKey}={$city}");
        
        $weatherData = json_decode($response->getBody(), true);
        
        // Handle the weather data as needed
        
        return response()->json($weatherData);
    }
}