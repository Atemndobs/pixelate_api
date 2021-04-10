<?php


namespace App\Services;

use _HumbugBoxcb6a53192cfd\Nette\Neon\Exception;
use App\Events\DailyForecastEvent;
use App\Events\ForecastUpdatedEvent;
use App\Events\WeatherFetchEvent;
use App\Models\Forecast;
use App\Repositories\Contracts\ForecastRepositoryInterface;
use App\Repositories\Contracts\WeatherRepositoryInterface;
use Flugg\Responder\Exceptions\Http\HttpException;
use Flugg\Responder\Http\Responses\ErrorResponseBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class WeatherService
{

    /**
     * @var string
     */
    private string $baseUrl ;

    /**
     * @var string
     */
    private string $apiKey;

    private WeatherRepositoryInterface $weatherRepository;
    private ForecastRepositoryInterface $forecastRepository;

    /**
     * @param WeatherRepositoryInterface $weatherRepository
     * @param ForecastRepositoryInterface $forecastRepository
     */
    public function __construct(
        WeatherRepositoryInterface $weatherRepository,
        ForecastRepositoryInterface $forecastRepository
    ) {
        $this->baseUrl = config('weather.api.base_url');
        $this->apiKey = config('weather.api.key');
        $this->weatherRepository = $weatherRepository;
        $this->forecastRepository = $forecastRepository;
    }

    /**
     * @param string $city
     */
    public function fetchWeather(string $city)
    {
        $existing = $this->weatherRepository->findWhere(['name' => $city]);

        if (($existing->count() >=  1)) {
            $created = $existing->last()->created_at->timestamp;
            $timeFromNow = Carbon::now()->timestamp;
            // 5 mins = 3000    | 10 mins = 600 ...
            if ($timeFromNow - $created < 120) {  // 2 mins
                broadcast(new WeatherFetchEvent($existing->last()));
                return $existing->last();
            }
        }
        $url =  "{$this->baseUrl}weather?q={$city}&units=metric&APPID={$this->apiKey}";
        try {
            $request = Http::get($url);
            $this->weatherRepository->create($request->json());
            broadcast(new WeatherFetchEvent($existing->first()));
            return $request->json();
        } catch (HttpException $exception) {
            return responder()->error(404, $exception->getMessage());
        }
    }

    public function forecast(string $city)
    {
        $cord = $this->getCoordinates($city);
        $lat = $cord['lat'];
        $lon = $cord['lon'];

        $daily = 'hourly,minutely';
        $hourly = 'daily,minutely';
        $minutely = 'hourly,daily';
        $url = "{$this->baseUrl}onecall?lat={$lat}&lon={$lon}&units=metric&exclude={$hourly}&appid={$this->apiKey}";

        $existing = $this->retrieveForecast($city);
        if (!$existing) {
            $existing = $this->fetchForecast($url);
        }

        broadcast(new ForecastUpdatedEvent($existing));
        return $existing;
    }

    public function peakTemperature($city = 'Dusseldorf')
    {
        if (request('city')) {
            $city = request()->city;
        }

        $data = $this->retrieveForecast($city);
        if (!$data) {
            $this->forecast($city);
            $data = $this->retrieveForecast($city);
        }
        $forecast =  collect($data->hourly);


        $maxForecast =   $forecast->map(function ($hour) use ($data, $city) {
            $date = Carbon::createFromTimestamp($hour['dt'], $data->timezone);

            if ($date <  Carbon::tomorrow()) {
                return  [
                    'temp' => $hour['temp'],
                    'time' => $date->toDate()->format('D M d Y H:i:s'),
                    'city' => $city
                ];
            }
        })->filter()->max();

        broadcast(new DailyForecastEvent($maxForecast));
        return $maxForecast;
    }

    public function getLocation($position): array
    {
        $lat = $position['lat'];
        $lon = $position['lon'];

        $url = "https://geocode.xyz/{$lat},{$lon}?json=1";
        $request = Http::get($url);

        return $request->json();
    }

    /**
     * @param string $city
     * @return array|ErrorResponseBuilder
     */
    public function getCoordinates(string $city)
    {
        $weather =  $this->fetchWeather($city);

        $lat = $weather['coord']['lat'] ?? $weather->coord['lat'] ?? null;
        $lon = $weather['coord']['lon'] ?? $weather->coord['lon'] ?? null;

        return [
            'lat' => round($lat, 2),
            'lon' => round($lon, 2)
        ];
    }

    /**
     * @param $city
     * @return null|Forecast
     */
    public function retrieveForecast($city): ?Forecast
    {
        $existing = $this->forecastRepository->findWhere(
            $this->getCoordinates($city)
        );

        if (($existing->count() >=  1)) {
            $created = $existing->last()->created_at;
            $dayNow = Carbon::now()->format('l');
            $dayCreated = $created->format('l');

            if ($dayNow !== $dayCreated &&
                Carbon::now()->timestamp - $created->timestamp > 86400) {
                return null;
            }
            return $existing->last();
        }
        return null;
    }

    /**
     * @param string $url
     * @return array|ErrorResponseBuilder|mixed
     */
    public function fetchForecast(string $url)
    {
        try {
            $request = Http::get($url);
            $this->forecastRepository->create($request->json());
            return $request->json();
        } catch (HttpException $exception) {
            return responder()->error(404, $exception->getMessage());
        }
    }
}
