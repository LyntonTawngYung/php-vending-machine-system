<?php

require_once __DIR__ . '/Database.php';

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO products (name, price, quantity_available, description, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['price'], $data['quantity_available'], $data['description'], $data['category']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ?, quantity_available = ?, description = ?, category = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['price'], $data['quantity_available'], $data['description'], $data['category'], $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>