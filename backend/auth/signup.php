<?php
session_start();
include(__DIR__ . "/../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_email'] = $email;
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            $error = "Error signing up. Try again!";
        }
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📝 Sign Up - SwiftTrans Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/script.js"></script>
    <style>
        /* Custom Styles */
        .glass-bg {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .fade-in {
            opacity: 0;
            transform: translateY(-10px);
            animation: fadeIn 1s forwards ease-out;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .glow-on-hover:hover {
            box-shadow: 0px 0px 12px rgba(96, 165, 250, 0.8);
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex flex-col min-h-screen">

    <!-- 🌐 Navbar -->
    <header class="fixed top-0 w-full bg-gray-800 shadow-md z-50">
        <div class="flex justify-between items-center p-5">
            <h1 class="text-3xl font-extrabold tracking-wide flex items-center">
                🚚 SwiftTrans Logistics
            </h1>
            <nav>
                <a href="../../index.php" class="text-gray-300 hover:text-blue-400 text-lg transition-transform hover:scale-105">🏠 Home</a>
            </nav>
        </div>
    </header>

    <!-- 🌟 Signup Form Container -->
    <div class="flex justify-center items-center min-h-screen">
        <div class="glass-bg p-10 rounded-xl shadow-xl w-96 fade-in">
            <h2 class="text-3xl font-extrabold text-center mb-6">📝 Create Account</h2>

            <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-300 mb-2">👤 Full Name:</label>
                    <input type="text" name="name" required
                        class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-gray-300 mb-2">📧 Email:</label>
                    <input type="email" name="email" required
                        class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-gray-300 mb-2">🔒 Password:</label>
                    <input type="password" name="password" required
                        class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg text-lg font-bold tracking-wide transition-transform hover:scale-105 glow-on-hover">
                    🚀 Sign Up
                </button>
            </form>

            <p class="text-center mt-6 text-gray-400">👋 Already have an account? 
                <a href="login.php" class="text-blue-400 hover:underline hover:text-blue-300">Login Here</a>
            </p>

            <!-- 🏠 Home Button -->
            <a href="/bye/index.php"
                class="block mt-4 text-center bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-transform hover:scale-105">
                🏠 Back to Home
            </a>
        </div>
    </div>

    <!-- 📝 Footer -->
    <footer class="text-center p-5 bg-gray-800">
        <p>&copy; 2025 SwiftTrans Logistics. All Rights Reserved. 🌐</p>
    </footer>

</body>
</html>

