<?php
ob_start();
// Include TCPDF library
require_once(__DIR__ . "/../../vendor/autoload.php");
$pdf = new TCPDF();

// Database connection
include(__DIR__ . "/../../config.php");

// Get booking ID from URL
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Invalid booking ID.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$query = "SELECT * FROM bookings WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SwiftTrans Logistics');
$pdf->SetAuthor('SwiftTrans Logistics');
$pdf->SetTitle('Booking Receipt');
$pdf->SetSubject('Transport Booking Receipt');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Add company logo
$pdf->Image(__DIR__ . '/../../frontend/assets/images/logo.png', 85, 10, 30);
$pdf->Ln(30); // Space after logo

// Title with company name
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetTextColor(33, 150, 243); // Blue color
$pdf->Cell(0, 10, 'SwiftTrans Logistics', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 14);
$pdf->SetTextColor(0, 0, 0); // Black color
$pdf->Cell(0, 10, 'Transport Booking Receipt', 0, 1, 'C');
$pdf->Ln(10);

// Booking details with stylish table
$pdf->SetFont('helvetica', '', 12);
$booking_table = "
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #2196F3; color: #fff; text-align: left; }
        td { background-color: #f4f4f4; }
    </style>
    <table>
        <tr><th>Booking ID</th><td>{$booking['id']}</td></tr>
        <tr><th>Vehicle Type</th><td>" . ucwords(str_replace("_", " ", $booking['vehicle_type'])) . "</td></tr>
        <tr><th>Pickup Location</th><td>{$booking['pickup_location']}</td></tr>
        <tr><th>Drop Location</th><td>{$booking['drop_location']}</td></tr>
        <tr><th>Distance</th><td>{$booking['distance']} km</td></tr>
        <tr><th>Weight</th><td>{$booking['weight']} kg</td></tr>
        <tr><th>Fare</th><td>Rs{$booking['fare']}</td></tr>
        <tr><th>Status</th><td>" . ucfirst($booking['status']) . "</td></tr>
        <tr><th>Booking Date</th><td>{$booking['booking_date']}</td></tr>
    </table>
";
$pdf->writeHTML($booking_table, true, false, true, false, '');

// Thank you message
$pdf->Ln(15);
$pdf->SetFont('helvetica', 'I', 12);
$pdf->SetTextColor(33, 150, 243); // Blue again
$pdf->Cell(0, 10, 'Thank you for choosing SwiftTrans Logistics! 🚚', 0, 1, 'C');

// Output the PDF
$pdf->Output("Booking_Receipt_{$booking['id']}.pdf", 'D');
?>
