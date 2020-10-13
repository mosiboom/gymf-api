<?php

namespace App\Http\Controllers;

use App\Services\HomeConfService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function create()
    {
        $home_data = \request()->input('home_data', '');
        if (!$home_data) return ReturnAPI();
        return HomeConfService::set($home_data);
    }

    public function get()
    {
        return HomeConfService::get();
    }
}
