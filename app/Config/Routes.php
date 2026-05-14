<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('api/weather-by-latlon','WeatherController::getWeatherByLatLon',['as' => "weather.latlon"]);

$routes->get('api/weather-by-city','WeatherController::getWeatherByCityName',['as' => "weather.city"]);