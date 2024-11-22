<?php

namespace App\Http\Controllers;

use Guardian\GuardianAPI;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Auth\AuthRepositoryInterface;


class HomeController extends Controller
{
    public function index()
    {

        $api = new GuardianAPI('842d07aa-80dc-4554-9d1f-2daf75bd7b31');
        $responses = $api->content()->setOrderBy("relevance")->fetch();

        // echo "<pre>";
        // print_r($response->response->results);

        $response = $api->singleItem()
        ->setId("/film/2021/oct/28/harry-potter-and-the-philosophers-stone-review")
        ->setShowStoryPackage(true)
        ->setShowEditorsPicks(true)
        ->setShowMostViewed(true)
        ->setShowRelated(true)
        ->fetch();
        $collection = collect($responses->response->results);

       

        $collection->each(function($item) {
            echo "<pre>";
            echo "<br>";
            echo date('Y-m-d H:i:s', strtotime($item->webPublicationDate));
            echo "<br>";
            echo str_replace("/", "-", $item->id);//end(explode("/", $item->id));
            print_r($item);
        });

        echo "<pre>";
        print_r($responses->response->results);
        exit;



        $newsapi = new NewsApi('f9f3c51ede6e4b67a7264cd80b343bdb');

        $getCountries = $newsapi->getCountries();
        $getLanguages = $newsapi->getLanguages();
        $getCategories = $newsapi->getCategories(); 
        $getSources = $newsapi->getSources('general', 'en', 'us');
      

        $collection = collect($getSources->sources);

       

        $collection->each(function($item) {
            echo "<br>";
            print_r($item);
        });
       // dd($modifiedCollection);
     //   dd($getSources);

    }
}
