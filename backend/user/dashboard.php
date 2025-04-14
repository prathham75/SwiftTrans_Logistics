<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
include(__DIR__ . "/../../config.php");

$user_id = $_SESSION['user_id'];

// ✅ Check if database connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ✅ Fetch user details securely with error handling
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);  // Debugging error
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// ✅ Fetch user bookings securely
$booking_stmt = $conn->prepare("
    SELECT b.id, f.service AS vehicle_type, b.pickup_location, b.drop_location, 
           b.distance, b.weight, b.fare, b.status 
    FROM bookings b
    JOIN fares f ON b.vehicle_type = f.id
    WHERE b.user_id = ?
");
if (!$booking_stmt) {
    die("Error preparing booking query: " . $conn->error);  // Debugging error
}
$booking_stmt->bind_param("i", $user_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚚 Dashboard - SwiftTrans Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Styles */
        body {
            font-family: 'Poppins', sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .neon-text {
            text-shadow: 0 0 10px #38bdf8, 0 0 20px #38bdf8;
        }

        .btn-glow:hover {
            box-shadow: 0 0 15px rgba(96, 165, 250, 0.8);
        }

        .table-hover:hover {
            background-color: rgba(255, 255, 255, 0.05);
            transition: background-color 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

    <!-- 🚀 Navbar -->
    <header class="fixed top-0 w-full bg-gray-800 shadow-lg z-50">
        <div class="flex justify-between items-center px-10 py-4">
            <h1 class="text-3xl font-extrabold neon-text">🚚 SwiftTrans Logistics</h1>
            <nav>
                <a href="../../index.php" class="mx-4 text-gray-300 hover:text-blue-400 text-lg transition-transform hover:scale-105">🏠 Home</a>
                <a href="dashboard.php" class="mx-4 text-blue-400 text-lg font-bold underline">📊 Dashboard</a>
                <a href="booking_history.php" class="mx-4 text-gray-300 hover:text-blue-400 text-lg transition-transform hover:scale-105">📝 Booking History</a>
                <a href="booking.php" class="mx-4 text-gray-300 hover:text-blue-400 text-lg transition-transform hover:scale-105">🚐 Book Transport</a>
                <a href="../auth/logout.php" class="mx-4 text-red-400 hover:text-red-500 text-lg transition-transform hover:scale-105">🚪 Logout</a>
            </nav>
        </div>
    </header>

    <!-- 🌟 Welcome Section -->
    <section class="text-center mt-24 py-12">
        <h2 class="text-5xl font-extrabold mb-2">👋 Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p class="text-gray-400 text-lg">📧 Your registered email: <?php echo htmlspecialchars($email); ?></p>
    </section>

    <!-- 📦 Booking Details -->
    <section class="max-w-7xl mx-auto bg-gray-800 p-10 rounded-xl shadow-xl glass-effect">
        <h3 class="text-4xl font-bold mb-8 text-center neon-text">📦 Your Bookings</h3>
        
        <?php if ($booking_result->num_rows > 0) { ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="p-4">🚗 Vehicle</th>
                            <th class="p-4">📍 Pickup</th>
                            <th class="p-4">📍 Drop</th>
                            <th class="p-4">📏 Distance (km)</th>
                            <th class="p-4">⚖️ Weight (kg)</th>
                            <th class="p-4">💰 Fare (₹)</th>
                            <th class="p-4 text-center">📊 Status</th>
                            <th class="p-4 text-center">🧾 Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $booking_result->fetch_assoc()) { ?>
                            <tr class="border-b border-gray-700 table-hover">
                                <td class="p-4"><?php echo htmlspecialchars(ucwords(str_replace("_", " ", $row['vehicle_type']))); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($row['pickup_location']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($row['drop_location']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($row['distance']); ?> km</td>
                                <td class="p-4"><?php echo isset($row['weight']) ? htmlspecialchars($row['weight']) . ' kg' : 'N/A'; ?></td>
                                <td class="p-4 font-bold">₹<?php echo htmlspecialchars($row['fare']); ?></td>
                                <td class="p-4 text-center">
                                    <?php 
                                        switch ($row['status']) {
                                            case 'pending':
                                                echo '<span class="text-yellow-400 font-semibold">🕒 Pending</span>';
                                                break;
                                            case 'on the way':
                                                echo '<span class="text-blue-400 font-semibold">🚚 On the Way</span>';
                                                break;
                                            case 'dispatched':
                                                echo '<span class="text-purple-400 font-semibold">📦 Dispatched</span>';
                                                break;
                                            case 'out for delivery':
                                                echo '<span class="text-orange-400 font-semibold">🏁 Out for Delivery</span>';
                                                break;
                                            case 'delivered':
                                                echo '<span class="text-green-400 font-semibold">✅ Delivered</span>';
                                                break;
                                            default:
                                                echo '<span class="text-gray-400 font-semibold">❓ Unknown</span>';
                                                break;
                                        }
                                    ?>
                                </td>
                                <td class="p-4 text-center">
                                    <?php if (isset($row['id'])) { ?>
                                        <a href="download_receipt.php?booking_id=<?php echo htmlspecialchars($row['id']); ?>" 
                                           class="bg-blue-500 text-white px-4 py-2 rounded-lg btn-glow hover:bg-blue-600 transition-transform hover:scale-105">
                                            📥 Download
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-gray-400">🚫 No Receipt Available</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p class="text-center text-gray-400 text-xl mt-8">📭 No bookings found. Start your journey now!</p>
        <?php } ?>
    </section>

    <!-- 🌐 Footer -->
    <footer class="text-center mt-12 p-6 bg-gray-800">
        <p>&copy; 2025 SwiftTrans Logistics. All Rights Reserved. 🌐</p>
    </footer>

</body>
</html>
