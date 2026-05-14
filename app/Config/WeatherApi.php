<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class WeatherApi extends BaseConfig
{
    public string $apiKey;
    public string $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey  = env('WEATHER_API_KEY', '');
        $this->baseUrl = env('WEATHER_API_BASE_URL', '');
    }
}
