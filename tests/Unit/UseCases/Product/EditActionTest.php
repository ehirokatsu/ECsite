<?php

namespace Tests\Unit\UseCases\Product;

use Tests\TestCase;
use App\UseCases\Product\EditAction;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Exception;

class EditActionTest extends TestCase
{
    use RefreshDatabase;

    protected $productRepositoryMock;
    protected $editAction;

    protected function setUp(): void
    {
        parent::setUp();

        // ProductRepositoryInterface のモックを作成
        $this->productRepositoryMock = Mockery::mock(ProductRepositoryInterface::class);
        $this->editAction = new EditAction($this->productRepositoryMock);
    }

    public function test_invoke_returns_product_when_found()
    {
        // Arrange: リポジトリモックのメソッド設定
        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->once()
            ->andReturn(collect([
                (object)['id' => 1, 'name' => 'Product 1'],
            ]));

        $result = $this->editAction->__invoke('1');

        // Assert
        $this->assertCount(1, $result);
    }

    public function test_invoke_throws_product_not_found_exception_when_not_found()
    {
        // Assert Exceptionにまとめらてしまうからエラーにならない？
        $this->expectException(Exception::class);

        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->with('999')
            ->andThrow(Exception::class);

        // Act
        $this->editAction->__invoke('999');
    }

    public function test_invoke_throws_generic_exception_for_other_errors()
    {
        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Some error');

        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->with('error')
            ->andThrow(new Exception('Some error'));

        // Act
        $this->editAction->__invoke('error');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}