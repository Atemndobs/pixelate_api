<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use Illuminate\Http\Request;

class PriceTrackerController extends Controller
{
    /**
     * @var ScraperService
     */
    public ScraperService $tracker;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * PriceTrackerController constructor.
     * @param ScraperService $tracker
     * @param Request $request
     */
    public function __construct(ScraperService $tracker, Request $request)
    {
        $this->tracker = $tracker;
        $this->request = $request;
    }


    public function index()
    {
        $article = $this->request->name;
        $price = $this->tracker->search($article);
        return responder()->success([
            'article' => $article,
            'price' => $price
        ]);
    }

    public function check()
    {
        $article = $this->request->article;
        $spec = $this->request->spec;

        $price = $this->tracker->check($article);
        return responder()->success([
            'article' => $article,
            'details' => $price
        ]);
    }
}
