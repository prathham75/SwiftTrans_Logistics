<?php
session_start();
include(__DIR__ . "/../../config.php");


// Fetch vehicle data
$vehicle_counts = [];
$vehicle_result = $conn->query("SELECT vehicle_type, available_count FROM vehicles");
while ($row = $vehicle_result->fetch_assoc()) {
    $vehicle_counts[$row['vehicle_type']] = intval($row['available_count']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['vehicle_type']) && isset($_POST['update_count'])) {
        $vehicle_type = $_POST['vehicle_type'];
        $update_count = intval($_POST['update_count']);

        // Update vehicle count (Prevent negative values)
        $sql = "UPDATE vehicles SET available_count = GREATEST(0, available_count + ?) WHERE vehicle_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $update_count, $vehicle_type);
        
        if ($stmt->execute()) {
            echo "✅ Vehicle count updated successfully!";
        } else {
            echo "❌ Error updating vehicle count!";
        }
        $stmt->close();
        exit; // Stop further execution (used for AJAX response)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Vehicles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">
    <!-- Navbar -->
 <header class="flex justify-between items-center p-5 shadow-md">
        <h1 class="text-3xl font-extrabold tracking-wide">🛠️ Manage Vehicle Availability </h1>
        <a href="admin_dashboard.php" class="btn text-white">⬅️ Back to Dashboard</a>
    </header>
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold text-center">🛠️ Manage Vehicle Availability</h2>

        <div class="space-y-6 mt-6">
            <?php foreach ($vehicle_counts as $vehicle => $count) { ?>
                <div class="flex justify-between items-center bg-gray-800 p-4 rounded-lg">
                    <p class="text-lg"><?php echo ucfirst($vehicle); ?>: <strong id="<?php echo $vehicle; ?>_count"><?php echo $count; ?></strong></p>
                    <div class="flex space-x-2">
                        <button onclick="updateVehicle('<?php echo $vehicle; ?>', 1)" class="bg-green-500 hover:bg-green-600 px-3 py-2 rounded">➕ Add</button>
                        <button onclick="updateVehicle('<?php echo $vehicle; ?>', -1)" class="bg-red-500 hover:bg-red-600 px-3 py-2 rounded">➖ Remove</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function updateVehicle(vehicleType, change) {
            fetch('admin_manage_vehicles.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'vehicle_type=' + vehicleType + '&update_count=' + change
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchVehicleAvailability();
            })
            .catch(error => console.error('Error updating vehicle:', error));
        }

        function fetchVehicleAvailability() {
            fetch('fetch_vehicles.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById("bike_count").innerText = data.bike || 0;
                    document.getElementById("small_tempo_count").innerText = data.small_tempo || 0;
                    document.getElementById("truck_count").innerText = data.truck || 0;
                })
                .catch(error => console.error('Error fetching vehicle data:', error));
        }

        // Auto-refresh availability every 1 seconds
        setInterval(fetchVehicleAvailability, 0000);
        fetchVehicleAvailability();
    </script>
</body>
</html>
