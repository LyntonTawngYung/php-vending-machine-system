<?php

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/TransactionModel.php';
require_once __DIR__ . '/../helpers/ApiAuthHelper.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';

class ProductsApiController {
    private $productModel;
    private $transactionModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->transactionModel = new TransactionModel();
    }

    #[Route('/api/products', methods: ['GET'])]
    public function index() {
        header('Content-Type: application/json');
        ApiAuthHelper::requireAuth();
        $products = $this->productModel->getAll();
        echo json_encode($products);
    }

    #[Route('/api/products/{id}', methods: ['GET'])]
    public function show($id) {
        header('Content-Type: application/json');
        ApiAuthHelper::requireAuth();
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        echo json_encode($product);
    }

    #[Route('/api/products', methods: ['POST'])]
    public function create() {
        header('Content-Type: application/json');
        ApiAuthHelper::requireRole('admin');
        $input = json_decode(file_get_contents('php://input'), true);
        $data = [
            'name' => $input['name'] ?? '',
            'price' => $input['price'] ?? 0,
            'quantity_available' => $input['quantity_available'] ?? 0,
            'description' => $input['description'] ?? '',
            'category' => $input['category'] ?? ''
        ];
        $errors = ValidationHelper::validateProduct($data);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }
        $id = $this->productModel->create($data);
        echo json_encode(['id' => $id, 'message' => 'Product created']);
    }

    #[Route('/api/products/{id}', methods: ['PUT'])]
    public function update($id) {
        header('Content-Type: application/json');
        ApiAuthHelper::requireRole('admin');
        $input = json_decode(file_get_contents('php://input'), true);
        $data = [
            'name' => $input['name'] ?? '',
            'price' => $input['price'] ?? 0,
            'quantity_available' => $input['quantity_available'] ?? 0,
            'description' => $input['description'] ?? '',
            'category' => $input['category'] ?? ''
        ];
        $errors = ValidationHelper::validateProduct($data);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }
        $result = $this->productModel->update($id, $data);
        if ($result > 0) {
            echo json_encode(['message' => 'Product updated']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    #[Route('/api/products/{id}', methods: ['DELETE'])]
    public function delete($id) {
        header('Content-Type: application/json');
        ApiAuthHelper::requireRole('admin');
        $result = $this->productModel->delete($id);
        if ($result > 0) {
            echo json_encode(['message' => 'Product deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    #[Route('/api/products/{productId}/purchase', methods: ['POST'])]
    public function purchase($productId) {
        header('Content-Type: application/json');
        $user = ApiAuthHelper::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);
        $quantity = $input['quantity'] ?? 1;
        $product = $this->productModel->getById($productId);
        if (!$product || $product['quantity_available'] < $quantity) {
            http_response_code(400);
            echo json_encode(['error' => 'Insufficient stock']);
            return;
        }
        $totalPrice = $product['price'] * $quantity;
        // Update product quantity
        $newQuantity = $product['quantity_available'] - $quantity;
        $this->productModel->update($productId, ['quantity_available' => $newQuantity]);
        // Log transaction
        $transactionData = [
            'user_id' => $user['id'],
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => 'completed'
        ];
        $transactionId = $this->transactionModel->create($transactionData);
        echo json_encode(['transaction_id' => $transactionId, 'message' => 'Purchase successful']);
    }
}