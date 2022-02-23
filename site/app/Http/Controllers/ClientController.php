<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $data = Client::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
            ->when(!isset($request->sort_by), function ($query) {
                $query->latest();
            })
            ->when($request->search, function ($query, $value) {
                $query->where('name', 'LIKE', '%'.$value.'%');
            })
            ->paginate($request->page_size ?? 10);

        return Inertia::render('client/index', [
            'items' => $data,
        ]);
    }

    public function store(Request $request){
        // dd($request);
        // return redirect()->back()->with('message', 'User Added Successfully.');
        // return new JsonResponse('', 200);
        /*
        if(request()->wantsJson()) {
            return 'ok!';
        }
        */

        $data = $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // insert into DB..

        // notifications are at layout level
        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'OK!',
        ]);
    }
}
