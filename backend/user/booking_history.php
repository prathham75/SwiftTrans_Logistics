<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
include(__DIR__ . "/../../config.php");


$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History - SwiftTrans 🚚</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/script.js"></script>
    <style>
        /* Custom Styles */
        body {
            background: #0f172a;
            font-family: 'Arial', sans-serif;
        }

        header {
            background: #1e293b;
            border-bottom: 2px solid #475569;
        }

        table {
            border-radius: 12px;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        th {
            background: #334155;
            text-transform: uppercase;
        }

        tr:hover {
            background: #1e293b;
            transition: background 0.3s ease;
        }

        td, th {
            padding: 12px;
            border-bottom: 1px solid #475569;
        }

        .btn {
            background: #2563eb;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background: #3b82f6;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="text-white">

    <!-- Navbar -->
    <header class="flex justify-between items-center p-5 shadow-md">
        <h1 class="text-3xl font-extrabold tracking-wide">📚 Booking History</h1>
        <a href="dashboard.php" class="btn text-white">⬅️ Back to Dashboard</a>
    </header>

    <!-- Booking History Section -->
    <section class="p-8 max-w-7xl mx-auto">
        <h2 class="text-4xl font-bold text-center mb-10">📦 Your Recent Bookings</h2>
        
        <?php if ($result->num_rows > 0) { ?>
        <div class="overflow-x-auto">
            <table class="w-full text-center shadow-lg bg-gray-800 rounded-lg">
                <thead>
                    <tr>
                        <th>🆔 ID</th>
                        <th>🚗 Vehicle Type</th>
                        <th>📍 Pickup Location</th>
                        <th>📍 Drop Location</th>
                        <th>📏 Distance (km)</th>
                        <th>💰 Total Fare (₹)</th>
                        <th>📊 Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr class="hover:bg-gray-700 transition duration-150">
                            <td class="p-3 font-bold text-blue-400"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars(ucfirst($row['vehicle_type'])); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['pickup_location']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['drop_location']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['distance']); ?> km</td>
                            <td class="p-3 text-green-400 font-semibold">₹<?php echo htmlspecialchars($row['fare']); ?></td>
                            <td class="p-3">
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
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
            <p class="text-center text-gray-400 text-lg">📭 No bookings found. Start shipping today!</p>
        <?php } ?>
    </section>

    <!-- Footer -->
    <footer class="text-center py-6 mt-10 bg-gray-800">
        <p>© 2025 SwiftTrans Logistics. 🚚 All Rights Reserved.</p>
    </footer>

</body>
</html>

