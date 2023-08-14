<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use Inertia\Inertia;


class VueController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();

        return Inertia::render('index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('create');
    }

    public function store(Request $request)
    {
        //dd($request);

        $products = Product::all();

        return Inertia::render('index', [
            'products' => $products,
        ]);
    }
}
