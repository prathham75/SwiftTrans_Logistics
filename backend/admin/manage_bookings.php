<?php
session_start();
include(__DIR__ . "/../../config.php");

$sql = "SELECT * FROM bookings"; // Ensure the table exists
$result = $conn->query($sql);

// 🔥 Debugging Step: Check for Errors
if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - SwiftTrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-10">

    <!-- Title -->
    <h2 class="text-4xl font-bold text-center mb-8">🚚 Manage Bookings</h2>

    <!-- Back to Dashboard Button -->
    <div class="mb-6 text-center">
        <a href="/bye/backend/admin/admin_dashboard.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200 ease-in-out">
            ⬅️ Back to Dashboard
        </a>
    </div>

    <!-- Bookings Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-700">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="border border-gray-600 p-3">Booking ID</th>
                    <th class="border border-gray-600 p-3">Vehicle</th>
                    <th class="border border-gray-600 p-3">Pickup Location</th>
                    <th class="border border-gray-600 p-3">Drop Location</th>
                    <th class="border border-gray-600 p-3">Distance (km)</th>
                    <th class="border border-gray-600 p-3 text-center">Status</th>
                    <th class="border border-gray-600 p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr class="bg-gray-700 text-white hover:bg-gray-600 transition duration-150">
                    <td class="border border-gray-600 p-3"><?= htmlspecialchars($row['id']) ?></td>
                    <td class="border border-gray-600 p-3"><?= ucfirst(htmlspecialchars($row['vehicle_type'])) ?></td>
                    <td class="border border-gray-600 p-3"><?= htmlspecialchars($row['pickup_location']) ?></td>
                    <td class="border border-gray-600 p-3"><?= htmlspecialchars($row['drop_location']) ?></td>
                    <td class="border border-gray-600 p-3"><?= htmlspecialchars($row['distance']) ?> km</td>
                    <td class="border border-gray-600 p-3 text-center">
                        <?php 
                            // 📝 Full status text with colors and emojis
                            switch ($row['status']) {
                                case 'pending':
                                    echo '<span class="bg-yellow-500 text-black px-3 py-1 rounded-lg font-semibold">🕒 Pending</span>';
                                    break;
                                case 'on the way':
                                    echo '<span class="bg-blue-500 text-white px-3 py-1 rounded-lg font-semibold">🚚 On the Way</span>';
                                    break;
                                case 'dispatched':
                                    echo '<span class="bg-purple-500 text-white px-3 py-1 rounded-lg font-semibold">📦 Dispatched</span>';
                                    break;
                                case 'out for delivery':
                                    echo '<span class="bg-orange-500 text-white px-3 py-1 rounded-lg font-semibold">🏁 Out for Delivery</span>';
                                    break;
                                case 'delivered':
                                    echo '<span class="bg-green-500 text-white px-3 py-1 rounded-lg font-semibold">✅ Delivered</span>';
                                    break;
                                default:
                                    echo '<span class="bg-gray-500 text-white px-3 py-1 rounded-lg font-semibold">❓ Unknown</span>';
                                    break;
                            }
                        ?>
                    </td>
                    <td class="border border-gray-600 p-3 text-center">
                        <a href="update_booking.php?id=<?= htmlspecialchars($row['id']) ?>" class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-150">
                            ✏️ Update
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
