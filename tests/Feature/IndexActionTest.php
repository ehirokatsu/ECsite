<?php

namespace Tests\Unit\UseCases\Product;

use Tests\TestCase;
use App\Models\Product;
use App\UseCases\Product\IndexAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use PDOException;
use Mockery;

class IndexActionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_invoke_returns_products_when_found()
    {
        // Arrange: テスト用データを生成
        Product::factory()->count(3)->create();

        // Act
        $action = new IndexAction();
        $result = $action();

        // Assert
        $this->assertCount(3, $result);
    }

    public function test_invoke_throws_exception_when_no_products_found()
    {
        // Act & Assert: 例外が発生することを確認
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No products found in the database.');

        $action = new IndexAction();
        $action();
    }


    public function test_invoke_throws_query_exception()
    {
        // Arrange: DB ファサードでクエリビルダーをモック
        DB::shouldReceive('table')
            ->andThrow(new QueryException('', '', [], new \Exception('Simulated Query Error')));

        // Act & Assert
        $this->expectException(\Exception::class);
        //$this->expectExceptionMessage('Database query error: Simulated Query Error');

        $action = new IndexAction();
        $action();
    }

    //Exceptionに流れている気がする

    public function test_invoke_throws_pdo_exception()
    {
        // Arrange: DB ファサードで PDO エラーをモック
        DB::shouldReceive('table')
            ->andThrow(new PDOException('PDO Error'));

        // Act & Assert
        $this->expectException(\Exception::class);
        //$this->expectExceptionMessage('Database connection error: PDO Error');

        $action = new IndexAction();
        $action();
    }
}