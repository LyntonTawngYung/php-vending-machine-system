<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <?php if (!AuthHelper::isAdmin()): ?>
            <p class="text-red-600 text-center">Access denied. Admin only.</p>
        <?php else: ?>
            <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Delete Product</h1>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                <p>Are you sure you want to delete the product "<strong><?php echo htmlspecialchars($product['name']); ?></strong>"?</p>
                <p class="mt-2">This action cannot be undone.</p>
            </div>
            <form action="/products/<?php echo $product['id']; ?>" method="post" class="space-y-4">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Yes, Delete</button>
            </form>
            <div class="mt-4 text-center">
                <a href="/products" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>