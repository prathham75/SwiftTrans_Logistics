<?php
session_start();

include(__DIR__ . "/../../config.php");


// Fetch all users
$result = $conn->query("SELECT id, name, email FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Manage Users - SwiftTrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/script.js"></script>
</head>
<body class="bg-gray-900 text-white">

    <header class="flex justify-between items-center p-5 bg-gray-800 shadow-lg border-b border-gray-700">
        <h1 class="text-3xl font-extrabold flex items-center">✨ Manage Users
        </h1>
        <a href="admin_dashboard.php" class="text-gray-300 hover:text-white transition duration-300 ease-in-out">⬅️ Back to Dashboard</a>
    </header>

    <section class="p-8 max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-4 flex items-center">📊 User List</h2>
        <div class="overflow-hidden rounded-lg shadow-lg">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-gray-300 uppercase text-sm leading-normal">
                        <th class="p-3">ID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr class="bg-gray-700 text-center hover:bg-gray-600 transition duration-200">
                            <td class="p-3 font-semibold">🆔 <?php echo $row['id']; ?></td>
                            <td class="p-3">👤 <?php echo $row['name']; ?></td>
                            <td class="p-3">📧 <?php echo $row['email']; ?></td>
                            <td class="p-3">
                                <a href="javascript:void(0);" 
                                   onclick="if(confirm('Are you sure you want to delete this user? 🚫')) 
                                            window.location.href='delete_user.php?id=<?php echo $row['id']; ?>';"
                                   class="text-red-400 hover:text-red-300 transition duration-200 font-semibold">
                                    ❌ Delete
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>
