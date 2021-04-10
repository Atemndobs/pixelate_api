<?php

namespace App\Console\Commands;

use App\Events\ForecastUpdatedEvent;
use App\Http\Controllers\WeatherController;
use App\Repositories\Contracts\ForecastRepositoryInterface;
use App\Repositories\Contracts\WeatherRepositoryInterface;
use App\Services\WeatherService;
use Illuminate\Console\Command;

class UpdateForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var ForecastRepositoryInterface
     */
    public ForecastRepositoryInterface $forecast;

    /**
     * @var WeatherRepositoryInterface
     */
    public WeatherRepositoryInterface $weather;

    /**
     * UpdateForecast constructor.
     * @param ForecastRepositoryInterface $forecast
     * @param WeatherRepositoryInterface $weather
     */
    public function __construct(ForecastRepositoryInterface $forecast, WeatherRepositoryInterface $weather)
    {
        $this->forecast = $forecast;
        $this->weather = $weather;
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws \JsonException
     */
    public function handle()
    {
        $forecast =  \DB::table('forecasts')->select('*')->latest()->first();

        if (!$forecast) {
            $weatherService = new WeatherService($this->weather, $this->forecast);
            $forecast = $weatherService->forecast('Dusseldorf');
        }
        $current = $forecast->current;

        $forecast->actual  = json_decode($current, true, 512, JSON_THROW_ON_ERROR);
        broadcast(new ForecastUpdatedEvent($forecast));
        return 0;
    }
}
