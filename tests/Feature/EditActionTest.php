<?php

namespace Tests\Unit\UseCases\Product;

use Tests\TestCase;
use App\Models\Product;
use App\UseCases\Product\EditAction;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ProductNotFoundException;
use Mockery;

class EditActionTest extends TestCase
{
    use RefreshDatabase;

    protected $productRepositoryMock;
    protected $editAction;

    protected function setUp(): void
    {
        parent::setUp();

        // ProductRepositoryInterface のモックを作成
        $this->productRepositoryMock = \Mockery::mock(ProductRepositoryInterface::class);
        $this->editAction = new EditAction($this->productRepositoryMock);
    }

    public function test_invoke_returns_product_when_found()
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

    public function test_invoke_throws_product_not_found_exception_when_not_found()
    {
        // Assert
        $this->expectException(ProductNotFoundException::class);

        $this->productRepositoryMock->shouldReceive('findOrFail')
            ->with('999')
            ->andThrow(ModelNotFoundException::class);

        // Act
        $this->editAction->__invoke('999');
    }

    public function test_invoke_throws_generic_exception_for_other_errors()
    {
        // Assert
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