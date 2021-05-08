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
     * @var mixed|string
     */
    private $city;

    /**
     * WeatherController constructor.
     * @param WeatherService $weather
     * @param Request $request
     */
    public function __construct(WeatherService $weather, Request $request)
    {
        $this->weather = $weather;
        $this->request = $request;
        $this->city = \request()->city ?? 'Dusseldorf';
    }


    public function index()
    {
        return $this->city;
    }

    public function store()
    {
        return $this->weather->fetchWeather($this->city);
    }

    public function forecast()
    {
        return $this->weather->forecast($this->city);
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
