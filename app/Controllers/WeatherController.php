<?php

namespace App\Controllers;

use App\Libraries\WeatherApiService;
use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class WeatherController extends BaseController
{
    protected WeatherApiService $weatherService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->weatherService = Services::weatherApi();
    }

    public function getWeatherByLatLon()
    {
        $input = $this->request->getGet();
        $rules = [
            'lat' => 'required|decimal',
            'lon' => 'required|decimal'
        ];
        if (! $this->validateData($input, $rules)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors(),
                    'message' => "Invalid location provided! Unable to process."
                ]);
        }
        $weatherData = $this->weatherService->getWeatherByCoordinates($input['lat'], $input['lon']);
        if(!$weatherData){
            return $this->response->setStatusCode(502)->setJSON([
                'status'  => 'error',
                'message' => 'Unable to fetch weather data at this time. Please try again later.'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $weatherData
        ]);
    }

    public function getWeatherByCityName(){
        $input = $this->request->getGet();
        $rules = [
            'city_name' => 'required|string',
        ];
        if (! $this->validateData($input, $rules)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors(),
                    'message' => "Empty city name provided"
                ]);
        }
        $cities = $this->weatherService->getGeocodeCity($input["city_name"]);

        $weathers = [];
        foreach($cities as $city){
            $city_weather = $this->weatherService->getWeatherByCoordinates($city['lat'], $city['lon']);
            $city_weather["city_name"] = $city["name"];
            $weathers[] = $city_weather;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $weathers
        ]);
    }
}
