<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Product\SearchAction;
use App\UseCases\Product\DeleteAction;
use App\Models\Product;

class ApiProductController extends Controller
{
    //
    public function __construct(
        DeleteAction $deleteAction,
        SearchAction $searchAction,
        )//use必須
    {
        $this->deleteAction = $deleteAction;
        $this->searchAction = $searchAction;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::all();
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        ($this->deleteAction)($id);
        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function search(Request $request) {

        $query = $request->input('query');
        $products = ($this->searchAction)($query);

        //axiosでAjaxを実現する時の戻りはJSONにする
        return response()->json($products);

    }
}
