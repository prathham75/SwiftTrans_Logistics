<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . "/../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;

            // FIX: Only redirect if not already on the dashboard
            if ($_SERVER['REQUEST_URI'] !== "/bye/backend/admin/admin_dashboard.php") {
                header("Location: /bye/backend/admin/admin_dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
            header("Location: /bye/backend/admin/admin_login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No admin found with this email!";
        header("Location: /bye/backend/admin/admin_login.php");
        exit();
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Admin Login | SwiftTrans</title>
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

        nav a {
            transition: color 0.3s ease, transform 0.2s ease;
        }

        nav a:hover {
            color: #3b82f6;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="text-white flex justify-center items-center min-h-screen">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 flex justify-between items-center p-5 shadow-md">
        <h1 class="text-3xl font-extrabold tracking-wide">🚚 SwiftTrans Logistics</h1>
        <nav>
            <a href="/bye/backend/admin/admin_login.php" class="mx-3 text-gray-300 hover:text-blue-400">🔐 Admin Panel</a>
        </nav>
    </header>

    <!-- Admin Login Form -->
    <div class="form-container p-8 rounded-lg w-96 mt-20">
        <h2 class="text-4xl font-extrabold text-center mb-6">🔑 Admin Login</h2>
        <?php if (isset($error)) { echo "<p class='text-red-500 text-center font-semibold'>$error</p>"; } ?>
        <form method="POST" action="/bye/backend/admin/admin_dashboard.php">
            <label class="block text-gray-300 mb-2 text-lg">📧 Email:</label>
            <input type="email" name="email" placeholder="admin@swifttrans.com" required 
                   class="w-full p-3 mb-4 rounded bg-gray-700 text-white focus:ring-2 ring-blue-500">

            <label class="block text-gray-300 mb-2 text-lg">🔒 Password:</label>
            <input type="password" name="password" placeholder="••••••••" required 
                   class="w-full p-3 mb-6 rounded bg-gray-700 text-white focus:ring-2 ring-blue-500">

            <button type="submit" class="w-full btn text-white rounded-lg text-lg">🚀 Login</button>
        </form>

        <!-- Home Button -->
        <a href="/bye/index.php" class="block text-center mt-6 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg text-lg">
            🏠 Go to Home
        </a>
    </div>

    <!-- Footer -->
    <footer class="fixed bottom-0 left-0 right-0 text-center py-4 bg-gray-800">
        <p>© 2025 SwiftTrans Logistics. 🌐 All Rights Reserved.</p>
    </footer>
</body>
</html>
