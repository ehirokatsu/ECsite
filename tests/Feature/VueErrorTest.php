<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//factoryで使用
use App\Models\Product;
use App\Models\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use App\UseCases\Image\CheckFileExists;
use App\Http\Controllers\VueController;
use App\UseCases\Product\StoreAction;
use App\UseCases\Product\IndexAction;
use App\UseCases\Product\EditAction;
use App\UseCases\Product\UpdateAction;
use App\UseCases\Product\DeleteAction;
use App\UseCases\Product\SearchAction;

class VueErrorTest extends TestCase
{
    //テスト用データベースを初期化する。これがないと以前のテストデータが残る
    use RefreshDatabase;


    protected $checkFileExists;
    protected $dependencies;

    protected function mockDependencies()
    {
        return [
            'storeAction' => \Mockery::mock(StoreAction::class),
            'indexAction' => \Mockery::mock(IndexAction::class),
            'editAction' => \Mockery::mock(EditAction::class),
            'updateAction' => \Mockery::mock(UpdateAction::class),
            'deleteAction' => \Mockery::mock(DeleteAction::class),
            'searchAction' => \Mockery::mock(SearchAction::class),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkFileExists = $this->app->make(CheckFileExists::class);

        //\Log::shouldReceive('error')->once()->with('Error : Test Exception');
        $this->dependencies = $this->mockDependencies();
    }

    /**
     * A basic feature test example.
     */
    public function test_index_catches_exception_and_redirects()
    {
        \Log::shouldReceive('error')
        ->once()
        ->with('Error : Test Exception');

        $this->dependencies['indexAction']->shouldReceive('__invoke')
        ->andThrow(new \Exception('Test Exception'));

        //array_valueは連想配列から配列の値のみを抽出する
        //スプレッド演算子「...」でstoreAction等を順番にVueControllerのコンストラクタに渡すことが可能
        $controller = new VueController(...array_values($this->dependencies));

        //VueControllerは今回作成したモックで動作させる
        $this->app->instance(VueController::class, $controller);

        $response = $this->get(route('vue.index'));
        $response->assertRedirect(route('vue.index'));
        $response->assertSessionHas('message', '商品の内容を取得できませんでした');

    }

    public function test_store_catches_exception_and_redirects()
    {
        //Log出力用のモック兼、アサート。
        \Log::shouldReceive('error')
        ->once()
        ->with('Error : Test Exception');

        $this->dependencies['storeAction']->shouldReceive('__invoke')
        ->andThrow(new \Exception('Test Exception'));

        //array_valueは連想配列から配列の値のみを抽出する
        //スプレッド演算子「...」でstoreAction等を順番にVueControllerのコンストラクタに渡すことが可能
        $controller = new VueController(...array_values($this->dependencies));

        //VueControllerは今回作成したモックで動作させる
        $this->app->instance(VueController::class, $controller);

        \Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        $response = $this->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);

        $response->assertRedirect(route('vue.index'));
        $response->assertSessionHas('message', '商品を追加できませんでした');

    }
    
}
