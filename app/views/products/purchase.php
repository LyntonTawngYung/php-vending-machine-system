<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
        <div class="flex justify-end mb-4">
            <form action="/logout" method="post">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Logout</button>
            </form>
        </div>
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Purchase Product</h1>
        <?php if (!$product): ?>
            <p class="text-red-600 text-center">Product not found.</p>
        <?php else: ?>
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($product['name']); ?></h2>
                <div class="space-y-1 text-gray-600">
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
                    <p><strong>Available Quantity:</strong> <?php echo htmlspecialchars($product['quantity_available']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                </div>
            </div>
            <form action="/products/<?php echo $product['id']; ?>/purchase" method="post" class="space-y-4">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $product['quantity_available']; ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Purchase</button>
            </form>
            <div class="mt-4 text-center">
                <a href="/products" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>