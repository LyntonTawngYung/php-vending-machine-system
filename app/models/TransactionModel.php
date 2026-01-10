<?php

require_once __DIR__ . '/Database.php';

class TransactionModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM transactions");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_price, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['user_id'], $data['product_id'], $data['quantity'], $data['total_price'], $data['status'] ?? 'pending']);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        if (isset($data['user_id'])) {
            $fields[] = "user_id = ?";
            $values[] = $data['user_id'];
        }
        if (isset($data['product_id'])) {
            $fields[] = "product_id = ?";
            $values[] = $data['product_id'];
        }
        if (isset($data['quantity'])) {
            $fields[] = "quantity = ?";
            $values[] = $data['quantity'];
        }
        if (isset($data['total_price'])) {
            $fields[] = "total_price = ?";
            $values[] = $data['total_price'];
        }
        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $values[] = $data['status'];
        }
        if (empty($fields)) return 0;
        $values[] = $id;
        $stmt = $this->db->prepare("UPDATE transactions SET " . implode(', ', $fields) . " WHERE id = ?");
        $stmt->execute($values);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>