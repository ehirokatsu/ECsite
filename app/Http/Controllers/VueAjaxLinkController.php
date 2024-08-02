<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\CreateConfirmRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Inertia\Inertia;
use App\UseCases\Product\StoreAction;
use App\UseCases\Product\IndexAction;
use App\UseCases\Product\EditAction;
use App\UseCases\Product\UpdateAction;
use App\UseCases\Product\DeleteAction;
use App\UseCases\Product\SearchAction;

class VueAjaxLinkController extends Controller
{
    
    //
    public function __construct(
        StoreAction $storeAction,
        IndexAction $indexAction,
        EditAction $editAction,
        UpdateAction $updateAction,
        DeleteAction $deleteAction,
        SearchAction $searchAction,
        )//use必須
    {

        $this->storeAction = $storeAction;
        $this->indexAction = $indexAction;
        $this->editAction = $editAction;
        $this->updateAction = $updateAction;
        $this->deleteAction = $deleteAction;
        $this->searchAction = $searchAction;
    }
    
    //
    public function index()
    {
        //コンストラクタインジェクションの場合、__inovkeを呼び出すには以下にように記載。
        //($this->saveImage)();
        //$this->saveImage->__invoke();
        //メソッドインジェクションなら以下だけで良い
        //$saveImage();

        $products = ($this->indexAction)();

        return Inertia::render('AjaxLink/index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('create');
    }

    public function store(CreateConfirmRequest $request)
    {
        //保存処理を行う
        ($this->storeAction)($request);
        //session()->flash('status', 'Task was successful!');

        return redirect()->route('vue.index')->with('message', '追加しました');
    }

    public function edit(string $id)
    {
        $product = ($this->editAction)($id);
        return Inertia::render('edit', [
            'product' => $product
        ]);
    }

    //画像の選択は任意なのでUpdateRequestにする
    public function update(UpdateRequest $request, string $id)
    {

        ($this->updateAction)($request, $id);
        return redirect()->route('vue.index')->with('message', '更新しました');

    }

    public function destroy(string $id) {

        ($this->deleteAction)($id);
        return redirect()->route('vue.index')->with('message', '削除しました');
    }

    public function search(Request $request) {

        
        $query = $request->input('query');
        //\Log::info($query);
        $products = ($this->searchAction)($query);
        //\Log::info($products);

        //axiosでAjaxを実現する時の戻りはJSONにする
        //return response()->json($products);
        
        //useFormでAjaxを実現する時の戻りは通常と同じ。
        return Inertia::render('index', [
            'products' => $products,
        ]);
        

    }

}
