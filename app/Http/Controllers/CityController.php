<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::apiNinjas()->get('/city?limit=5&name=' . $request->input('search'));
        $cities = $response->json();

        $cityArray = array_map(function ($city) {
            return [
                'label' => $city['name'] . ', ' . $city['country'],
            ];
        }, $cities);

        return [
            'cities' => $cityArray
        ];
    }
}
