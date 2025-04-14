<?php
session_start();
include(__DIR__ . "/../../config.php");

// Check if admin is logged in

// Get user ID from URL and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare delete statement to avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error preparing statement: " . $conn->error;
    }
} else {
    $_SESSION['error_message'] = "Invalid user ID.";
}

// Redirect back to the manage users page
header("Location: manage_users.php");
exit();
?>
