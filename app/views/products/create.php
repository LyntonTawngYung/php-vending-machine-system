<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
        <div class="flex justify-end mb-4">
            <form action="/logout" method="post">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Logout</button>
            </form>
        </div>
        <?php if (!AuthHelper::isAdmin()): ?>
            <p class="text-red-600 text-center">Access denied. Admin only.</p>
        <?php else: ?>
            <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Create New Product</h1>
            <?php if (isset($_SESSION['validation_errors'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php foreach ($_SESSION['validation_errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['validation_errors']); ?>
            <?php endif; ?>
            <form action="/products" method="post" id="productForm" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="quantity_available" class="block text-sm font-medium text-gray-700">Quantity Available:</label>
                    <input type="number" id="quantity_available" name="quantity_available" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="description" name="description" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                    <input type="text" id="category" name="category" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Create Product</button>
            </form>
            <div class="mt-4 text-center">
                <a href="/products" class="text-indigo-600 hover:text-indigo-900">Back to List</a>
            </div>
        <?php endif; ?>
    </div>
    <script>
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const quantity = parseInt(document.getElementById('quantity_available').value);
            const description = document.getElementById('description').value.trim();
            const category = document.getElementById('category').value.trim();

            let errors = [];
            if (!name) errors.push('Name is required.');
            if (isNaN(price) || price <= 0) errors.push('Price must be a positive number.');
            if (isNaN(quantity) || quantity < 0) errors.push('Quantity available must be non-negative.');
            if (!description) errors.push('Description is required.');
            if (!category) errors.push('Category is required.');

            if (errors.length > 0) {
                e.preventDefault();
                alert('Validation errors:\n' + errors.join('\n'));
            }
        });
    </script>
</body>
</html>