<?php

namespace App\Console\Commands;

use App\Events\PeakTemperatureEvent;
use App\Repositories\Contracts\ForecastRepositoryInterface;
use App\Repositories\Contracts\WeatherRepositoryInterface;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class CheckPeakForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:peak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Peak Forecast';

    /**
     * @var ForecastRepositoryInterface
     */
    protected ForecastRepositoryInterface $forecastRepository;

    protected WeatherRepositoryInterface $weatherRepository;

    /**
     * CheckPeakForecast constructor.
     * @param ForecastRepositoryInterface $forecastRepository
     * @param WeatherRepositoryInterface $weatherRepository
     */
    public function __construct(
        ForecastRepositoryInterface $forecastRepository,
        WeatherRepositoryInterface $weatherRepository
    ) {
        $this->forecastRepository = $forecastRepository;
        $this->weatherRepository = $weatherRepository;
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @param WeatherService $weatherService
     * @return int
     */
    public function handle(WeatherService $weatherService)
    {
        $peakTemp = $weatherService->peakTemperature();
        dump($peakTemp);

        $currentTemp = $this->weatherRepository->findWhere([
            'name' => 'Dusseldorf'
        ])->last()->main['temp'];

        if ($currentTemp >= $peakTemp['temp']) {
            broadcast(new PeakTemperatureEvent($peakTemp));
        }
        return 0;
    }
}
