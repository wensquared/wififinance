<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InfoController extends Controller
{
    public function index()
    {
        

        return view('info');
    }

    public function search(Request $resquest)
    {
        // dd($resquest->all());

        $client = new Client();
        $url = "https://api.tiingo.com/iex/?tickers=".$resquest->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);

        $result = json_decode($res->getBody()->getContents());
        dd($result);
    }
}
