<?php
session_start();

if (!isset($_SESSION['booking_info'])) {
    echo "❌ No booking information available!";
    exit;
}

$booking = $_SESSION['booking_info'];

// Company details
$company_name = "SwiftTrans Logistics";
$company_contact = "📞 +91 98765 43210";
$company_email = "📧 support@swifttrans.com";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - SwiftTrans Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold text-center mb-6">🧾 Booking Receipt</h2>

        <!-- Company Details -->
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold"><?= $company_name ?></h3>
            <p><?= $company_contact ?></p>
            <p><?= $company_email ?></p>
        </div>

        <!-- Booking Details -->
        <div class="space-y-2">
            <h3 class="text-lg font-bold">🚚 Booking Information</h3>
            <p class="text-lg mb-4"><strong>🆔 Booking ID:</strong> <?= htmlspecialchars($booking['id'] ?? 'N/A') ?></p>
            <p><strong>🚗 Vehicle Type:</strong> <?= ucfirst($booking['vehicle_type']) ?></p>
            <p><strong>📍 Pickup Location:</strong> <?= htmlspecialchars($booking['pickup_location']) ?></p>
            <p><strong>🎯 Drop Location:</strong> <?= htmlspecialchars($booking['drop_location']) ?></p>
            <p><strong>🛤️ Distance:</strong> <?= $booking['distance'] ?> km</p>
            <p><strong>⚖️ Parcel Weight:</strong> <?= $booking['weight'] ?> kg</p>
            <p><strong>💰 Total Fare:</strong> ₹<?= $booking['fare'] ?></p>
        </div>

        <hr class="my-4">

        <!-- Sender Details -->
        <div class="space-y-2">
            <h3 class="text-lg font-bold">📤 Sender Information</h3>
            <p><strong>👤 Name:</strong> <?= htmlspecialchars($booking['sender_name']) ?></p>
            <p><strong>📞 Contact:</strong> <?= htmlspecialchars($booking['sender_contact']) ?></p>
            <p><strong>🏠 Location:</strong> <?= htmlspecialchars($booking['sender_location']) ?></p>
        </div>

        <hr class="my-4">

        <!-- Receiver Details -->
        <div class="space-y-2">
            <h3 class="text-lg font-bold">📦 Receiver Information</h3>
            <p><strong>👤 Name:</strong> <?= htmlspecialchars($booking['receiver_name']) ?></p>
            <p><strong>📞 Contact:</strong> <?= htmlspecialchars($booking['receiver_contact']) ?></p>
            <p><strong>🏠 Location:</strong> <?= htmlspecialchars($booking['receiver_location']) ?></p>
        </div>

        <hr class="my-4">

        <!-- Print Button -->
        <div class="text-center mt-6">
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200 ease-in-out">
                🖨️ Print Receipt
            </button>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="/bye/backend/user/dashboard.php" class="text-blue-500 hover:underline">⬅️ Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
