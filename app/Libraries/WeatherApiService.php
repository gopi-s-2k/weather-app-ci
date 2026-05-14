<?php

namespace App\Libraries;

use Config\WeatherApi;
use Exception;

class WeatherApiService{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = new WeatherApi();
        
        $this->client = \Config\Services::curlrequest([
            'baseURI' => $this->config->baseUrl,
            'timeout' => 5,
        ]);
    }

    public function getWeatherByCoordinates(float $lat, float $lon): ?array
    {
        try {
            $response = $this->client->get('data/2.5/weather', [
                'query' => [
                    'lat'   => $lat,
                    'lon'   => $lon,
                    'appid' => $this->config->apiKey,
                    'units' => 'metric'
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
            return null;
        } catch (Exception $e) {
            log_message('error', '[Weather API] Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getGeocodeCity(string $city_name): ?array{
        try {
            $response = $this->client->get('geo/1.0/direct', [
                'query' => [
                    'q'   => $city_name,
                    'limit'   => 3,
                    'appid' => $this->config->apiKey
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
            return null;
        } catch (Exception $e) {
            log_message('error', '[Weather API] Failed: ' . $e->getMessage());
            return null;
        }
    }
}