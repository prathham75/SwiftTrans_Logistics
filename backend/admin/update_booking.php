<?php
session_start();
include(__DIR__ . "/../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // 🔥 Debug Step
    if (empty($booking_id) || empty($status)) {
        die("❌ Missing booking ID or status");
    }

    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    if (!$stmt) {
        die("❌ SQL Error: " . $conn->error);
    }

    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "✅ Status updated successfully!";
    } else {
        echo "❌ Update failed or no changes made.";
    }

    $stmt->close();
    header("Location: manage_bookings.php"); // Redirect after update
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $result = $conn->query("SELECT status FROM bookings WHERE id = $booking_id");

    if (!$result) {
        die("❌ SQL Error: " . $conn->error);
    }

    $booking = $result->fetch_assoc();
} else {
    header("Location: manage_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking - SwiftTrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex justify-center items-center h-screen">

    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-4">🚚 Update Booking Status</h2>

        <form method="POST" action="">
            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking_id) ?>">

            <label class="block text-gray-300 mb-2 font-medium">📋 Select Status:</label>
            <select name="status" required class="w-full p-2 mb-4 rounded bg-gray-700 text-white">
                <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : '' ?>>🕒 Pending</option>
                <option value="on the way" <?= $booking['status'] == 'on the way' ? 'selected' : '' ?>>🚚 On the Way</option>
                <option value="dispatched" <?= $booking['status'] == 'dispatched' ? 'selected' : '' ?>>📦 Dispatched</option>
                <option value="out for delivery" <?= $booking['status'] == 'out for delivery' ? 'selected' : '' ?>>🏁 Out for Delivery</option>
                <option value="delivered" <?= $booking['status'] == 'delivered' ? 'selected' : '' ?>>✅ Delivered</option>
            </select>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition duration-150">
                💾 Save Changes
            </button>
        </form>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="manage_bookings.php" class="text-blue-400 hover:underline">⬅️ Back to Manage Bookings</a>
        </div>
    </div>

</body>
</html>
