<?php

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/TransactionModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';

class ProductsController {
    private $productModel;
    private $transactionModel;

    public function __construct($productModel = null, $transactionModel = null) {
        $this->productModel = $productModel ?: new ProductModel();
        $this->transactionModel = $transactionModel ?: new TransactionModel();
    }

    #[Route('/', methods: ['GET'])]
    // Home page - redirect to products
    public function home() {
        header('Location: /products');
        exit;
    }

    #[Route('/products', methods: ['GET'])]
    // List all products
    public function index() {
        AuthHelper::requireLogin();
        $products = $this->productModel->getAll();
        include __DIR__ . '/../views/products/list.php';
    }

    #[Route('/products/create', methods: ['GET'])]
    // Show create form (admin only)
    public function createForm() {
        AuthHelper::requireRole('admin');
        include __DIR__ . '/../views/products/create.php';
    }

    #[Route('/products/{id}', methods: ['GET'])]
    // Show single product - redirect to list
    public function show($id) {
        AuthHelper::requireLogin();
        header('Location: /products');
        exit;
    }

    #[Route('/products', methods: ['POST'])]
    // Create product (admin only)
    public function create() {
        AuthHelper::requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'quantity_available' => $_POST['quantity_available'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'category' => $_POST['category'] ?? ''
        ];
        $errors = ValidationHelper::validateProduct($data);
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            header('Location: /products/create');
            exit;
        }
        $id = $this->productModel->create($data);
        header('Location: /products?success=create');
        exit;
    }

    #[Route('/products/{id}/edit', methods: ['GET'])]
    // Show edit form (admin only)
    public function editForm($id) {
        AuthHelper::requireRole('admin');
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            echo 'Product not found';
            return;
        }
        include __DIR__ . '/../views/products/edit.php';
    }

    #[Route('/products/{id}', methods: ['PUT'])]
    // Update product (admin only)
    public function update($id) {
        AuthHelper::requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['_method'] ?? '') !== 'PUT') {
            return false;
        }
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'quantity_available' => $_POST['quantity_available'] ?? 0,
            'description' => $_POST['description'] ?? '',
            'category' => $_POST['category'] ?? ''
        ];
        $errors = ValidationHelper::validateProduct($data);
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            header('Location: /products/' . $id . '/edit');
            exit;
        }
        $result = $this->productModel->update($id, $data);
        if ($result > 0) {
            header('Location: /products?success=update');
            exit;
        } else {
            // Handle update failure, maybe redirect with error
            header('Location: /products/' . $id . '/edit?error=update_failed');
            exit;
        }
    }

    #[Route('/products/{id}/delete', methods: ['GET'])]
    // Show delete confirmation (admin only)
    public function deleteForm($id) {
        AuthHelper::requireRole('admin');
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            echo 'Product not found';
            return;
        }
        include __DIR__ . '/../views/products/delete.php';
    }

    #[Route('/products/{id}', methods: ['DELETE'])]
    // Delete product (admin only)
    public function delete($id) {
        AuthHelper::requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['_method'] ?? '') !== 'DELETE') {
            return false;
        }
        $result = $this->productModel->delete($id);
        if ($result > 0) {
            header('Location: /products?success=delete');
            exit;
        } else {
            header('Location: /products/' . $id . '/delete?error=delete_failed');
            exit;
        }
    }

    #[Route('/products/{id}/purchase', methods: ['GET'])]
    // Show purchase form
    public function purchaseForm($id) {
        AuthHelper::requireLogin();
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            echo 'Product not found';
            return;
        }
        include __DIR__ . '/../views/products/purchase.php';
    }

    #[Route('/products/{productId}/purchase', methods: ['POST'])]
    // Purchase product
    public function purchase($productId) {
        AuthHelper::requireLogin();
        $quantity = $_POST['quantity'] ?? 1;
        $user = AuthHelper::getCurrentUser();
        $product = $this->productModel->getById($productId);
        if (!$product || $product['quantity_available'] < $quantity) {
            // Handle error, maybe redirect with error
            header('Location: /products/' . $productId . '/purchase?error=insufficient_quantity');
            exit;
        }
        $totalPrice = $product['price'] * $quantity;
        // Update product quantity
        $newQuantity = $product['quantity_available'] - $quantity;
        $updateData = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity_available' => $newQuantity,
            'description' => $product['description'],
            'category' => $product['category']
        ];
        $this->productModel->update($productId, $updateData);
        // Log transaction
        $transactionData = [
            'user_id' => $user['id'],
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => 'completed'
        ];
        $transactionId = $this->transactionModel->create($transactionData);
        // Redirect to success or list
        header('Location: /products?success=purchase');
        exit;
    }
}
?>