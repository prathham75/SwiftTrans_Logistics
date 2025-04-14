<?php
include(__DIR__ . "/../../config.php");

$vehicle_counts = [];
$vehicle_result = $conn->query("SELECT vehicle_type, available_count FROM vehicles");
while ($row = $vehicle_result->fetch_assoc()) {
    $vehicle_counts[$row['vehicle_type']] = intval($row['available_count']);
}

header('Content-Type: application/json');
echo json_encode($vehicle_counts);
?>
