<?php
session_start();

include(__DIR__ . "/../../config.php");

// Fetch total bookings
$result = $conn->query("SELECT COUNT(*) FROM bookings");
$total_bookings = $result->fetch_row()[0];

// Fetch total users
$result = $conn->query("SELECT COUNT(*) FROM users");
$total_users = $result->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SwiftTrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        .card-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white">

    <!-- Navbar -->
    <header class="flex justify-between items-center p-5 bg-gray-800 shadow-lg fade-in">
        <h1 class="text-3xl font-extrabold tracking-wide text-blue-400">🚚 SwiftTrans Admin</h1>
        <nav>
            <a href="/bye/index.php" class="mx-3 text-gray-300 hover:text-blue-400 transition duration-200">🏠 Home</a>
            <a href="manage_users.php" class="mx-3 text-gray-300 hover:text-blue-400 transition duration-200">👥 Manage Users</a>
            <a href="manage_bookings.php" class="mx-3 text-gray-300 hover:text-blue-400 transition duration-200">📦 Manage Bookings</a>
            <a href="update_booking.php" class="mx-3 text-gray-300 hover:text-blue-400 transition duration-200">🔄 Update Bookings</a>
            <a href="update_fares.php" class="mx-3 text-gray-300 hover:text-blue-400 transition duration-200">💰 Update Fares</a>
            <a href="admin_manage_vehicles.php" class="mx-3 text-gray-300 hover:text-blue-400 transition duration-200">📦 Manage Vehicle Availability</a>
            <a href="../admin/admin_login.php" class="mx-3 text-red-400 hover:text-red-600 transition duration-200">🚪 Logout</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative text-center py-16 fade-in">
    <img src="../../frontend/assets/images/admin-bg.jpg" alt="Admin Dashboard" class="absolute inset-0 w-full h-full object-cover opacity-20">
        <div class="relative z-10">
            <h2 class="text-5xl font-extrabold text-blue-400 drop-shadow-lg">Welcome, Admin! 🎉</h2>
            <p class="text-gray-300 text-lg mt-4">Manage your users, bookings, fares, and operations with ease.</p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto py-16 px-8">
        <!-- Total Users Card -->
        <div class="bg-gray-800 p-8 rounded-xl shadow-xl text-center card-hover">
            <img src="../../frontend/assets/images/users-icon.png" alt="Users" class="w-20 h-20 mx-auto mb-4">
            <h3 class="text-3xl font-bold text-blue-400">👥 Total Users</h3>
            <p class="text-5xl font-extrabold mt-4"><?php echo $total_users; ?></p>
        </div>

        <!-- Total Bookings Card -->
        <div class="bg-gray-800 p-8 rounded-xl shadow-xl text-center card-hover">
            <img src="../../frontend/assets/images/bookings-icon.png" alt="Bookings" class="w-20 h-20 mx-auto mb-4">
            <h3 class="text-3xl font-bold text-blue-400">📦 Total Bookings</h3>
            <p class="text-5xl font-extrabold mt-4"><?php echo $total_bookings; ?></p>
        </div>

        <!-- Manage Users Card -->
        <div class="bg-blue-500 p-8 rounded-xl shadow-xl text-center card-hover">
            <h3 class="text-3xl font-bold text-white">🔧 Manage Users</h3>
            <p class="mt-4">View, edit, and manage user data easily.</p>
            <a href="manage_users.php" class="inline-block mt-6 bg-white text-blue-500 px-6 py-2 rounded-lg font-bold hover:bg-blue-100 transition duration-150">
                ➡️ Go to Users
            </a>
        </div>

        <!-- Manage Bookings Card -->
        <div class="bg-green-500 p-8 rounded-xl shadow-xl text-center card-hover">
            <h3 class="text-3xl font-bold text-white">📝 Manage Bookings</h3>
            <p class="mt-4">Track and update booking information.</p>
            <a href="manage_bookings.php" class="inline-block mt-6 bg-white text-green-500 px-6 py-2 rounded-lg font-bold hover:bg-green-100 transition duration-150">
                ➡️ Go to Bookings
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-6 bg-gray-800 text-gray-400">
        &copy; 2025 SwiftTrans Logistics. All rights reserved.
    </footer>

</body>
</html>
