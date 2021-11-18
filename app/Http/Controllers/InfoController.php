<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InfoController extends Controller
{
    public function index()
    {
        return view('info.index');
    }
    public function search(Request $resquest)
    {

        $client = new Client();

        $url = "https://api.tiingo.com/iex/?tickers=".$resquest->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);
        // $result[] = json_decode($res->getBody()->getContents());

        $tmp = json_decode($res->getBody()->getContents());
        $now_price = $tmp[0]->last;
        // dd($now_price);


        $date_now = date('Y-m-d');
        $date_week_ago = date('Y-m-d',strtotime('-1 week'));
        $date_month_ago = date('Y-m-d', strtotime('-1 month'));
        // dd($date_month_ago);
        $url = "https://api.tiingo.com/tiingo/daily/".$resquest->ticker."/prices?startDate=".$date_week_ago;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);
        // $result[] = json_decode($res->getBody()->getContents());
        $tmp = json_decode($res->getBody()->getContents());
        // dd($tmp);
        foreach ($tmp as $data) {
            $dates[] = $data->date;
            $close_prices[] = $data->close;
        }




        $url = "https://api.tiingo.com/tiingo/daily/".$resquest->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);
        // $result[] = json_decode($res->getBody()->getContents());
        $tmp = json_decode($res->getBody()->getContents());

        $ticker_name = $tmp->name;
        $description = $tmp->description;
        // dd($ticker_name);

    return view('info.result',compact('now_price','ticker_name','description'))->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))->with('close_prices',json_encode($close_prices,JSON_NUMERIC_CHECK));
    }
} 
