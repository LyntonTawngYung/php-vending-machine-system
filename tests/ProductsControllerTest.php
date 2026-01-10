<?php

use PHPUnit\Framework\TestCase;
use Mockery;

require_once __DIR__ . '/../app/controllers/ProductsController.php';
require_once __DIR__ . '/../app/models/ProductModel.php';
require_once __DIR__ . '/../app/models/TransactionModel.php';

class ProductsControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testIndexReturnsProductsWhenLoggedIn()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');
        $validationHelperMock = Mockery::mock('alias:ValidationHelper');

        $products = [['id' => 1, 'name' => 'Product 1']];
        $productModelMock->shouldReceive('getAll')->andReturn($products);
        $authHelperMock->shouldReceive('requireLogin')->once();

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->index();

        $this->assertEquals($products, $result);
    }

    public function testShowReturnsProductWhenExists()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $product = ['id' => 1, 'name' => 'Product 1'];
        $productModelMock->shouldReceive('getById')->with(1)->andReturn($product);
        $authHelperMock->shouldReceive('requireLogin')->once();

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->show(1);

        $this->assertEquals($product, $result);
    }

    public function testShowReturnsNullWhenProductNotExists()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $productModelMock->shouldReceive('getById')->with(1)->andReturn(null);
        $authHelperMock->shouldReceive('requireLogin')->once();

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->show(1);

        $this->assertNull($result);
    }

    public function testCreateReturnsIdWhenValidDataAndAdmin()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');
        $validationHelperMock = Mockery::mock('alias:ValidationHelper');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'New Product',
            'price' => 10.0,
            'quantity_available' => 100,
            'description' => 'Desc',
            'category' => 'Cat'
        ];

        $authHelperMock->shouldReceive('requireRole')->with('admin')->once();
        $validationHelperMock->shouldReceive('validateProduct')->andReturn([]);
        $productModelMock->shouldReceive('create')->andReturn(1);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->create();

        $this->assertEquals(1, $result);
    }

    public function testCreateReturnsFalseWhenValidationFails()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');
        $validationHelperMock = Mockery::mock('alias:ValidationHelper');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['name' => ''];

        $authHelperMock->shouldReceive('requireRole')->with('admin')->once();
        $validationHelperMock->shouldReceive('validateProduct')->andReturn(['name' => 'Name is required']);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->create();

        $this->assertFalse($result);
    }

    public function testCreateReturnsFalseWhenNotPost()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $authHelperMock->shouldReceive('requireRole')->with('admin')->once();

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->create();

        $this->assertFalse($result);
    }

    public function testUpdateReturnsTrueWhenValidDataAndAdmin()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');
        $validationHelperMock = Mockery::mock('alias:ValidationHelper');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'Updated Product',
            'price' => 15.0,
            'quantity_available' => 50,
            'description' => 'Updated Desc',
            'category' => 'Updated Cat'
        ];

        $authHelperMock->shouldReceive('requireRole')->with('admin')->once();
        $validationHelperMock->shouldReceive('validateProduct')->andReturn([]);
        $productModelMock->shouldReceive('update')->with(1, Mockery::type('array'))->andReturn(1);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->update(1);

        $this->assertTrue($result);
    }

    public function testUpdateReturnsFalseWhenValidationFails()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');
        $validationHelperMock = Mockery::mock('alias:ValidationHelper');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['price' => -5];

        $authHelperMock->shouldReceive('requireRole')->with('admin')->once();
        $validationHelperMock->shouldReceive('validateProduct')->andReturn(['price' => 'Price must be positive']);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->update(1);

        $this->assertFalse($result);
    }

    public function testDeleteReturnsTrueWhenAdmin()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $authHelperMock->shouldReceive('requireRole')->with('admin')->once();
        $productModelMock->shouldReceive('delete')->with(1)->andReturn(1);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->delete(1);

        $this->assertTrue($result);
    }

    public function testPurchaseReturnsTransactionIdWhenSuccessful()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $product = ['id' => 1, 'name' => 'Product', 'price' => 10.0, 'quantity_available' => 10];
        $user = ['id' => 1];

        $authHelperMock->shouldReceive('requireLogin')->once();
        $authHelperMock->shouldReceive('getCurrentUser')->andReturn($user);
        $productModelMock->shouldReceive('getById')->with(1)->andReturn($product);
        $productModelMock->shouldReceive('update')->with(1, ['quantity_available' => 5])->once();
        $transactionModelMock->shouldReceive('create')->andReturn(123);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->purchase(1, 5);

        $this->assertEquals(123, $result);
    }

    public function testPurchaseReturnsFalseWhenProductNotExists()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $authHelperMock->shouldReceive('requireLogin')->once();
        $authHelperMock->shouldReceive('getCurrentUser')->andReturn(['id' => 1]);
        $productModelMock->shouldReceive('getById')->with(1)->andReturn(null);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->purchase(1, 5);

        $this->assertFalse($result);
    }

    public function testPurchaseReturnsFalseWhenInsufficientQuantity()
    {
        $productModelMock = Mockery::mock(ProductModel::class);
        $transactionModelMock = Mockery::mock(TransactionModel::class);
        $authHelperMock = Mockery::mock('alias:AuthHelper');

        $product = ['id' => 1, 'quantity_available' => 3];

        $authHelperMock->shouldReceive('requireLogin')->once();
        $authHelperMock->shouldReceive('getCurrentUser')->andReturn(['id' => 1]);
        $productModelMock->shouldReceive('getById')->with(1)->andReturn($product);

        $controller = new ProductsController($productModelMock, $transactionModelMock);
        $result = $controller->purchase(1, 5);

        $this->assertFalse($result);
    }
}