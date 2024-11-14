<?php

namespace Tests\Unit\UseCases\Product;

use Tests\TestCase;
use App\Models\Product;
use App\UseCases\Product\EditAction;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ProductNotFoundException;
use Mockery;



class EditActionTest extends TestCase
{

    use RefreshDatabase;

    /*リポジトリパターン導入前
    　Mockery::mock('App\Models\Product');でエラーになり、findOrFailでExceptionを発生できなかった

    public function test_invoke_returns_product_when_found()
    {
        // Arrange: テスト用データを生成
        $product = Product::factory()->create();

        // Act
        $action = new EditAction();
        $result = $action($product->id);

        // Assert
        $this->assertEquals($product->id, $result->id);
    }

    public function test_invoke_throws_exception_when_no_product_found()
    {
        // Arrange: 存在しないIDを設定
        $nonExistentId = 999;

        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $action = new EditAction();
        $action($nonExistentId);
    }


    public function test_invoke_throws_generic_exception2()
    {
        // Arrange: Product モデルの findOrFail メソッドをモック
        $mock = Mockery::mock('App\Models\Product');
        $mock->shouldReceive('findOrFail')->andThrow(new \Exception('Generic Exception'));

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Generic Exception');

        // Act
        $action = new EditAction();
        $action('1');
    }

*/
    protected $productRepositoryMock;
    protected $editAction;

    protected function setUp(): void
    {
        parent::setUp();

        // ProductRepository の完全なモックを作成
        $this->productRepositoryMock = \Mockery::mock(ProductRepository::class);
        $this->editAction = new EditAction($this->productRepositoryMock);
    }

    public function testInvokeReturnsProductWhenFound()
    {
        // Arrange
        $productMock = \Mockery::mock(Product::class);
        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->with('123')
            ->andReturn($productMock);

        // Act
        $result = $this->editAction->__invoke('123');

        // Assert
        $this->assertInstanceOf(Product::class, $result);
    }

    public function testInvokeThrowsProductNotFoundExceptionWhenNotFound()
    {
        $this->expectException(ProductNotFoundException::class);

        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->with('999')
            ->andThrow(ModelNotFoundException::class);

        // Act
        $this->editAction->__invoke('999');
    }

    public function testInvokeThrowsGenericExceptionForOtherErrors()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Some error');

        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->with('error')
            ->andThrow(new \Exception('Some error'));

        // Act
        $this->editAction->__invoke('error');
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}


