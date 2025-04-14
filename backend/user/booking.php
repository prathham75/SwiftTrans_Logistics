<?php
session_start();
include(__DIR__ . "/../../config.php");

$message = "";
$fares = [];

// Fetch updated fares from the database
$fare_result = $conn->query("SELECT * FROM fares");
while ($row = $fare_result->fetch_assoc()) {
    $fares[$row['service']] = floatval($row['price']);
}

// Define city distances
$city_distances = [
    "Sangli_Satara" => 200,
    "Sangli_Kolhapur" => 45,
    "Sangli_Solapur" => 250,
    "Sangli_Karad" => 50,
    "Satara_Kolhapur" => 200,
    "Satara_Solapur" => 300,
    "Satara_Karad" => 60,
    "Satara_Sangli" => 200,
    "Kolhapur_Solapur" => 300,
    "Kolhapur_Karad" => 55,
    "Kolhapur_Sangli" => 45,
    "Kolhapur_Satara" => 200,
    "Karad_Sangli" => 50,
    "Karad_Satara" => 60,
    "Karad_Solapur" => 275,
    "Karad_Sangli" => 50,
    "Solapur_Karad" => 275,
    "Solapur_Sangli" => 250,
    "Solapur_Kolhapur" => 300,
    "Solapur_Satara" => 300

];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['sender_name'], $_POST['sender_contact'], $_POST['sender_location'],
              $_POST['vehicle_type'], $_POST['pickup_location'], $_POST['drop_location'],
              $_POST['weight'], $_POST['receiver_name'], $_POST['receiver_contact'], $_POST['receiver_location'], $_POST['distance'])) {
        $message = "❌ Missing required fields!";
    } else {
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            $message = "❌ User not logged in!";
        } else {
            $sender_name = $_POST['sender_name'];
            $sender_contact = $_POST['sender_contact'];
            $sender_location = $_POST['sender_location'];
            $vehicle_type = $_POST['vehicle_type'];
            $pickup_location = $_POST['pickup_location'];
            $drop_location = $_POST['drop_location'];
            $weight = floatval($_POST['weight']);
            $receiver_name = $_POST['receiver_name'];
            $receiver_contact = $_POST['receiver_contact'];
            $receiver_location = $_POST['receiver_location'];
            $distance = floatval($_POST['distance']);

            if ($distance <= 0) {
                $message = "❌ Invalid distance calculated!";
            } else {
                // Get fare per km
                $fare_per_km = $fares[$vehicle_type] ?? null;
                if ($fare_per_km === null) {
                    $message = "❌ Invalid vehicle type!";
                } else {
                    $fare = $fare_per_km * $distance;

                    $sql = "INSERT INTO bookings (user_id, sender_name, sender_contact, sender_location, vehicle_type, pickup_location, drop_location, distance, weight, fare, receiver_name, receiver_contact, receiver_location) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt === false) {
                        $message = "❌ SQL Error: " . $conn->error;
                    } else {
                        $stmt->bind_param("issssssdddsis", $user_id, $sender_name, $sender_contact, $sender_location, $vehicle_type, $pickup_location, $drop_location, $distance, $weight, $fare, $receiver_name, $receiver_contact, $receiver_location);

                        if ($stmt->execute()) {
                            // Update vehicle availability
                            $update_vehicle_sql = "UPDATE vehicles SET available_count = available_count - 1 WHERE vehicle_type = ?";
                            $update_stmt = $conn->prepare($update_vehicle_sql);
                            $update_stmt->bind_param("s", $vehicle_type);
                            $update_stmt->execute();
                            $update_stmt->close();
                        
                            $message = "✅ Booking successful! Total fare: ₹" . number_format($fare, 2);
                            // Fetch available vehicles from the database
                            $vehicle_counts = [];
                            $vehicle_result = $conn->query("SELECT vehicle_type, available_count FROM vehicles");
                            while ($row = $vehicle_result->fetch_assoc()) {
                            $vehicle_counts[$row['vehicle_type']] = intval($row['available_count']);
}

                            $_SESSION['booking_info'] = [
                                'sender_name' => $sender_name,
                                'sender_contact' => $sender_contact,
                                'sender_location' => $sender_location,
                                'vehicle_type' => $vehicle_type,
                                'pickup_location' => $pickup_location,
                                'drop_location' => $drop_location,
                                'distance' => $distance,
                                'weight' => $weight,
                                'fare' => $fare,
                                'receiver_name' => $receiver_name,
                                'receiver_contact' => $receiver_contact,
                                'receiver_location' => $receiver_location
                            ];
                        
                            $message .= "<br><a href='/bye/backend/user/print_receipt.php' target='_blank' class='bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg mt-4 inline-block'>
                                            🖨️ Print Receipt
                                         </a>";
                        } else {
                            $message = "❌ Error: " . $stmt->error;
                        }
                        

                        $stmt->close();
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Transport - SwiftTrans Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

 <!-- Navbar -->
 <header class="flex justify-between items-center p-5 shadow-md">
        <h1 class="text-3xl font-extrabold tracking-wide">📚 Booking Center</h1>
        <a href="dashboard.php" class="btn text-white">⬅️ Back to Dashboard</a>
    </header>

<div class="container mx-auto p-8">
    <h2 class="text-3xl font-bold text-center">🚚 Book a Transport</h2>

    <?php if (!empty($message)) { ?>
        <p class="text-center text-green-400 font-semibold mb-4"><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST" action="" class="space-y-4">
        <input type="text" name="sender_name" placeholder="👤 Sender Name" required class="w-full p-2 rounded bg-gray-800">
        <input type="text" name="sender_contact" placeholder="📞 Sender Contact" required class="w-full p-2 rounded bg-gray-800">
        <input type="text" name="sender_location" placeholder="📍 Sender Location" required class="w-full p-2 rounded bg-gray-800">

        <select name="vehicle_type" required class="w-full p-2 rounded bg-gray-800">
            <?php foreach ($fares as $service => $price) { ?>
                <option value="<?php echo $service; ?>"><?php echo ucfirst($service); ?> (₹<?php echo number_format($price, 2); ?>/km)</option>
            <?php } ?>
        </select>
        <h3 class="text-xl font-bold text-center mt-6">🚗 Vehicle Availability</h3>
<div class="flex justify-center space-x-6 mt-4">
    <p class="bg-gray-800 p-3 rounded-lg">🏍️ Bikes: <strong id="bike_count"><?php echo $vehicle_counts['bike'] ?? 0; ?></strong></p>
    <p class="bg-gray-800 p-3 rounded-lg">🚚 Small Tempos: <strong id="small_tempo_count"><?php echo $vehicle_counts['small_tempo'] ?? 0; ?></strong></p>
    <p class="bg-gray-800 p-3 rounded-lg">🚛 Trucks: <strong id="truck_count"><?php echo $vehicle_counts['truck'] ?? 0; ?></strong></p>
</div>

        <select name="pickup_location" id="pickup_location" required class="w-full p-3 rounded-lg bg-gray-800 text-white">
            <option value="">📍 Select Pickup Location</option>
            <option value="Sangli">Sangli</option>
            <option value="Solapur">Solapur</option>
            <option value="Kolhapur">Kolhapur</option>
            <option value="Karad">Karad</option>
            <option value="Satara">Satara</option>
        </select>

        <select name="drop_location" id="drop_location" required class="w-full p-3 rounded-lg bg-gray-800 text-white">
            <option value="">🏁 Select Drop Location</option>
            <option value="Sangli">Sangli</option>
            <option value="Solapur">Solapur</option>
            <option value="Kolhapur">Kolhapur</option>
            <option value="Karad">Karad</option>
            <option value="Satara">Satara</option>
        </select>

        <input type="hidden" name="distance" id="distance">
        <p class="text-gray-400 text-center mt-2">📏 Distance: <span id="distance_display">0 km</span></p>
        
        <h3 class="text-lg font-bold">📦 Receiver Details</h3>
        <input type="text" name="receiver_name" placeholder="👤 Receiver Name" required class="w-full p-2 rounded bg-gray-800">
        <input type="text" name="receiver_contact" placeholder="📞 Receiver Contact" required class="w-full p-2 rounded bg-gray-800">
        <input type="text" name="receiver_location" placeholder="📍 Receiver Address" required class="w-full p-2 rounded bg-gray-800">
        <input type="number" step="0.1" name="weight" placeholder="⚖️ Parcel Weight (kg)" required class="w-full p-2 rounded bg-gray-800">
        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 p-2 rounded">🚚 Book Now</button>
    </form>
</div>

<script>
    const distances = <?php echo json_encode($city_distances); ?>;
    document.getElementById("pickup_location").addEventListener("change", updateDistance);
    document.getElementById("drop_location").addEventListener("change", updateDistance);

    function updateDistance() {
        let pickup = document.getElementById("pickup_location").value;
        let drop = document.getElementById("drop_location").value;
        let key = pickup + "_" + drop;
        document.getElementById("distance").value = distances[key] || 0;
        document.getElementById("distance_display").innerText = (distances[key] || 0) + " km";
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

    // Auto-refresh vehicle count every 10 seconds
    setInterval(fetchVehicleAvailability, 10000);
    fetchVehicleAvailability();

</script>

</body>
</html>
