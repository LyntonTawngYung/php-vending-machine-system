<?php

class ValidationHelper {
    public static function validateProduct($data) {
        $errors = [];

        // Name required
        if (empty(trim($data['name']))) {
            $errors['name'] = 'Name is required.';
        }

        // Price required and positive
        if (!isset($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Price must be a positive number.';
        }

        // Quantity available required and non-negative
        if (!isset($data['quantity_available']) || $data['quantity_available'] < 0) {
            $errors['quantity_available'] = 'Quantity available must be non-negative.';
        }

        // Description required
        if (empty(trim($data['description']))) {
            $errors['description'] = 'Description is required.';
        }

        // Category required
        if (empty(trim($data['category']))) {
            $errors['category'] = 'Category is required.';
        }

        return $errors;
    }
}
?>