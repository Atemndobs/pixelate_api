<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    /**
     * @var WeatherService
     */
    public WeatherService $weather;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * WeatherController constructor.
     * @param WeatherService $weather
     * @param Request $request
     */
    public function __construct(WeatherService $weather, Request $request)
    {
        $this->weather = $weather;
        $this->request = $request;
    }


    public function index()
    {
    }

    public function store()
    {
        $city = \request()->city;
        return $this->weather->fetchWeather($city);
    }

    public function forecast()
    {
        $city = $this->request->city ?? 'Dusseldorf';
        return $this->weather->forecast($city);
    }

    public function update()
    {
    }

    public function myLocation()
    {
        $position = $this->request->all();
        return $this->weather->getLocation($position);
    }

    public function peak()
    {
        return responder()->success([
            'peak' => $this->weather->peakTemperature()
        ]);
    }
}
