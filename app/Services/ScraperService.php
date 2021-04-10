<?php

namespace App\Services;

use Goutte\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function PHPUnit\Framework\stringContains;

class ScraperService
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * ScraperService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function search($query = 'Adobe 2020 Master mac')
    {
        $client = $this->client;

        $search = strtolower($query);

        $query = 'Adobe 2020 Master mac';

        $word = str_replace(' ', '+', ucwords($query));

        $searchUrl = "https://ssill.info/webshopssill/advanced_search_result.php?keywords=${word}";


        $crawler = $this->client->request('GET', $searchUrl);

        $elements = $crawler->filter('td > a')->each(function ($node) {
            $text[] = $node->text();
            return $text;
        });



        return $this->checkPrice('');
    }

    public function check(string $article)
    {
        $methods = get_class_methods($this);
        $function = collect($methods)->filter(function ($method) use ($article) {
            if (str_contains($method, ucwords($article))) {
                return $method;
            }
        })->first();

        return $this->$function();
    }

    public function checkPrice($urls = '')
    {
        $url = "https://ssill.info/webshopssill/adobe-mac-adobe-2020-master-mac-product-1738.html";

        $crawler = $this->client->request('GET', $url);
        return $crawler->filter('#display_price')->text();
    }

    public function checkIphone()
    {
        $spec = 64;
        //$url= "https://swappie.com/de-en/iphone/iphone-x/iphone-x-${spec}gb-space-gray-5/";

        $url = "https://swappie.com/en/model/iphone-x/";
        $crawler = $this->client->request('GET', $url);
        $sections = $crawler->filter('div > span')->each(function ($section) {
            return $text[] = $section->text();
        });

        $baseModel = collect($sections)->first();
        array_shift($sections);

        if (str_contains($baseModel, 'stock')) {
            array_shift($sections);
        }

        $results = array_chunk($sections, 4);
        $allModels = [];


        foreach (array_keys($results) as $key) {
            if ($key <= 8) {
                $allModels[] = $results[$key];
            }
        }

        $condition = 'Acceptable';
        $spec =  request('spec')??64;

        return collect($allModels)->map(function ($model) {
            return [
                'color' => $model[0],
                'spec' => $model[1],
                'condition' => $model[2],
                'price' => $model[3],
            ];
        })->filter(function ($model) use ($condition, $spec) {
            if ($model['condition'] === $condition && (int)$model['spec'] === (int)$spec) {
                return $model;
            }
        })->first();
    }

    public function checkBitcoin()
    {
        return 'TBD';
    }
}
