<?php
http_response_code(403); // Forbidden
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-4">Access Denied</h1>
        <p class="text-gray-700 mb-6">You do not have permission to access this page. Admin privileges are required.</p>
        <a href="/login" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Go to Login</a>
    </div>
</body>
</html>