<?php

namespace Tests\Unit\UseCases\Product;

use Tests\TestCase;
use App\Models\Product;
use App\UseCases\Product\IndexAction;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use PDOException;
use Mockery;
use Exception;

class IndexActionTest extends TestCase
{
    use RefreshDatabase;

    protected $productRepositoryMock;
    protected $editAction;
    protected $indexAction;
    protected function setUp(): void
    {
        parent::setUp();

        // ProductRepositoryInterface の完全なモックを作成
        $this->productRepositoryMock = \Mockery::mock(ProductRepositoryInterface::class);
        $this->indexAction = new IndexAction($this->productRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_invoke_returns_products_when_found()
    {
        // Arrange: リポジトリモックのメソッド設定
        $this->productRepositoryMock->shouldReceive('all')
            ->once()
            ->andReturn(collect([
                (object)['id' => 1, 'name' => 'Product 1'],
                (object)['id' => 2, 'name' => 'Product 2'],
                (object)['id' => 3, 'name' => 'Product 3'],
            ]));

        $result = $this->indexAction->__invoke();

        // Assert
        $this->assertCount(3, $result);
    }

    public function test_invoke_throws_exception_when_no_products_found()
    {
        // Arrange: 期待される戻り値を空のコレクションに設定
        $this->productRepositoryMock->shouldReceive('all')
            ->once()
            ->andReturn(collect());

        // Act & Assert: 例外が発生することを確認
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No products found in the database.');

        $this->indexAction->__invoke();
    }

    public function test_invoke_throws_query_exception()
    {
        // Arrange: リポジトリの all() メソッドが QueryException をスローするように設定
        $this->productRepositoryMock->shouldReceive('all')
            ->once()
            ->andThrow(new QueryException('', '', [], new \Exception('Simulated Query Error')));
    
        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database query error: Simulated Query Error');
    
        $this->indexAction->__invoke();
    }
    
    public function test_invoke_throws_pdo_exception()
    {
        // Arrange: リポジトリの all() メソッドが PDOException をスローするように設定
        $this->productRepositoryMock->shouldReceive('all')
            ->once()
            ->andThrow(new PDOException('PDO Error'));
    
        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database connection error: PDO Error');
    
        $this->indexAction->__invoke();
    }
}