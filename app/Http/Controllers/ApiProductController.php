<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Product\SearchAction;
use App\UseCases\Product\DeleteAction;
use App\Http\Requests\Product\CreateConfirmRequest;
use App\UseCases\Product\StoreAction;
use App\Models\Product;
use Illuminate\Http\JsonResponse; 
use App\UseCases\Product\EditAction;
use Inertia\Inertia;
use App\UseCases\Product\UpdateAction;
use App\Http\Requests\Product\UpdateRequest;

class ApiProductController extends Controller
{
    //
    public function __construct(
        StoreAction $storeAction,
        DeleteAction $deleteAction,
        SearchAction $searchAction,
        EditAction $editAction,
        UpdateAction $updateAction,
        )//use必須
    {
        $this->storeAction = $storeAction;
        $this->deleteAction = $deleteAction;
        $this->searchAction = $searchAction;
        $this->editAction = $editAction;
        $this->updateAction = $updateAction;
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
    public function store(CreateConfirmRequest $request)
    {
        try {
            // 保存処理を行う
            ($this->storeAction)($request);
    
            // 成功時のレスポンス
            return new JsonResponse(['message' => '商品が正常に追加されました。'], 200);
        } catch (\Exception $e) {
            // エラーレスポンス
            return new JsonResponse(['error' => '商品の追加に失敗しました。'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    //edit画面だけInertiaでレンダリングする
    public function edit(string $id)
    {
        //
        $product = ($this->editAction)($id);
        return Inertia::render('AjaxAxios/edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        //

        try {
            // 更新処理を行う
            ($this->updateAction)($request, $id);
    
            // 成功時のレスポンス
            return new JsonResponse(['message' => '商品が正常に更新されました。'], 200);
        } catch (\Exception $e) {
            // エラーレスポンス
            return new JsonResponse(['error' => '商品の更新に失敗しました。'], 500);
        }
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
