<?php
session_start();
include(__DIR__ . "/../../config.php");

// Fetch current fares
$result = $conn->query("SELECT * FROM fares");
$fares = [];
while ($row = $result->fetch_assoc()) {
    $fares[] = $row;
}

// Handle fare update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['fares'] as $id => $price) {
        $price = floatval($price);
        $conn->query("UPDATE fares SET price = $price WHERE id = $id");
    }
    header("Location: update_fares.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚚 Update Fares | SwiftTrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #0f172a;
            font-family: 'Arial', sans-serif;
        }

        header {
            background: #1e293b;
            border-bottom: 2px solid #475569;
        }

        .form-container {
            background: #1e293b;
            border: 1px solid #475569;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease-in-out;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        input:focus {
            border-color: #3b82f6;
            outline: none;
        }

        .btn {
            background: #2563eb;
            padding: 10px 0;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background: #3b82f6;
            transform: scale(1.05);
        }

        .success-message {
            background: #16a34a;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        table {
            width: 100%;
            background: #1f2937;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background: #374151;
            border-radius: 8px 8px 0 0;
        }

        td {
            background: #111827;
            border-radius: 8px;
        }

        input[type="number"] {
            width: 100%;
            padding: 8px;
            background: #374151;
            color: white;
            border: 1px solid #475569;
            border-radius: 8px;
        }
    </style>
</head>
<body class="text-white flex justify-center items-center min-h-screen">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 flex justify-between items-center p-5 shadow-md">
        <h1 class="text-3xl font-extrabold tracking-wide">💰 Update Fares</h1>
        <a href="admin_dashboard.php" class="text-gray-300 hover:text-blue-400 transition">⬅️ Back to Dashboard</a>
    </header>

    <!-- Update Fares Form -->
    <section class="form-container p-8 rounded-lg w-3/4 mt-20">
        <?php if (isset($_GET['success'])) { echo "<p class='success-message'>✅ Fares updated successfully!</p>"; } ?>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>🚗 Service</th>
                        <th>💲 Current Fare (₹/km)</th>
                        <th>📝 New Fare (₹/km)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $services = [
                        "Bike" => "🏍️ Bike Delivery",
                        "Small Tempo" => "🚚 Small Tempo",
                        "Truck" => "🚛 Truck Transport"
                    ];
                    foreach ($fares as $fare) {
                        $serviceIcon = $services[$fare['service']] ?? $fare['service'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($serviceIcon); ?></td>
                            <td>₹<?php echo number_format($fare['price'], 2); ?></td>
                            <td>
                                <input type="number" step="0.01" name="fares[<?php echo $fare['id']; ?>]" 
                                       value="<?php echo $fare['price']; ?>">
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" class="btn w-full rounded-lg text-lg mt-6">💾 Save Changes</button>
        </form>
    </section>

    <!-- Footer -->
    <footer class="fixed bottom-0 left-0 right-0 text-center py-4 bg-gray-800">
        <p>© 2025 SwiftTrans Logistics. 🌐 All Rights Reserved.</p>
    </footer>
</body>
</html>
