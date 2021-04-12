<?php

namespace App\Console\Commands;

use App\Events\PriceCheckEvent;
use App\Services\ScraperService;
use Illuminate\Console\Command;

class CheckPrice extends Command
{
    /**
     * @var ScraperService
     */
    private ScraperService $scraper;



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check article Price';

    /**
     * CheckPrice constructor.
     * @param ScraperService $scraper
     */
    public function __construct(ScraperService $scraper)
    {
        $this->scraper = $scraper;
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        broadcast(new PriceCheckEvent($this->scraper->checkIphone()));
        return 0;
    }
}
