<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Product List</h1>
            <form action="/logout" method="post" class="inline">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Logout</button>
            </form>
        </div>
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                <?php if ($_GET['success'] === 'purchase'): ?>
                    Product purchased successfully!
                <?php elseif ($_GET['success'] === 'create'): ?>
                    Product created successfully!
                <?php elseif ($_GET['success'] === 'update'): ?>
                    Product updated successfully!
                <?php elseif ($_GET['success'] === 'delete'): ?>
                    Product deleted successfully!
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (AuthHelper::isAdmin()): ?>
            <div class="mb-6 text-center">
                <a href="/products/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add New Product</a>
            </div>
        <?php endif; ?>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="?sort=name&order=asc" class="hover:text-gray-700">Name ↑</a>
                            <a href="?sort=name&order=desc" class="hover:text-gray-700 ml-2">↓</a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="?sort=price&order=asc" class="hover:text-gray-700">Price ↑</a>
                            <a href="?sort=price&order=desc" class="hover:text-gray-700 ml-2">↓</a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Available</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo htmlspecialchars($product['price'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($product['quantity_available'] ?? ''); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($product['description'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($product['category'] ?? ''); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/products/<?php echo $product['id']; ?>/purchase" class="text-indigo-600 hover:text-indigo-900 mr-4">Purchase</a>
                                <?php if (AuthHelper::isAdmin()): ?>
                                    <a href="/products/<?php echo $product['id']; ?>/edit" class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                                    <a href="/products/<?php echo $product['id']; ?>/delete" class="text-red-600 hover:text-red-900">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if (isset($pagination)): ?>
            <div class="mt-8 flex justify-center items-center space-x-4">
                <?php if ($pagination['prev']): ?>
                    <a href="?page=<?php echo $pagination['prev']; ?>&sort=<?php echo $_GET['sort'] ?? 'name'; ?>&order=<?php echo $_GET['order'] ?? 'asc'; ?>" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Previous</a>
                <?php endif; ?>
                <span class="text-gray-700">Page <?php echo $pagination['current']; ?> of <?php echo $pagination['total']; ?></span>
                <?php if ($pagination['next']): ?>
                    <a href="?page=<?php echo $pagination['next']; ?>&sort=<?php echo $_GET['sort'] ?? 'name'; ?>&order=<?php echo $_GET['order'] ?? 'asc'; ?>" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>