<?php
session_start();
include(__DIR__ . "/../../config.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔑 Login - SwiftTrans Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/script.js"></script>
    <style>
        /* Custom Styles */
        .glass-bg {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
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
            box-shadow: 0px 0px 10px rgba(96, 165, 250, 0.8);
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

    <!-- 🌟 Login Form Container -->
    <div class="flex justify-center items-center min-h-screen">
        <div class="glass-bg p-10 rounded-xl shadow-xl w-96 fade-in">
            <h2 class="text-3xl font-extrabold text-center mb-6">🔐 Login</h2>

            <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>

            <form method="POST" class="space-y-6">
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
                    🚀 Login
                </button>
            </form>

            <p class="text-center mt-6 text-gray-400">🆕 Don't have an account? 
                <a href="signup.php" class="text-blue-400 hover:underline hover:text-blue-300">Create One!</a>
            </p>
        </div>
    </div>

    <!-- 📝 Footer -->
    <footer class="text-center p-5 bg-gray-800">
        <p>&copy; 2025 SwiftTrans Logistics. All Rights Reserved. 🌐</p>
    </footer>

</body>
</html>


