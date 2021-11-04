<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InfoController extends Controller
{
    public function index()
    {
        $client = new Client();
        $url = "https://api.tiingo.com/iex/?tickers=aapl";
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);

        $result = json_decode($res->getBody()->getContents());
        dd($result);

        return view('info');
    }
}
