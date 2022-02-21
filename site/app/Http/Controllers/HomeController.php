<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Client;
use App\Models\Maintenance;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::get(['id' ,'name']);
        $clients = Client::get(['id' ,'name']);

        return Inertia::render('home', [
            'clients' => $clients,
            'brands' => $brands,
        ]);
    }
}
