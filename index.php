<?php
session_start();
include("config.php");
// Fetch updated fares from the database
$fare_result = $conn->query("SELECT * FROM fares");
$fares = [];
while ($row = $fare_result->fetch_assoc()) {
    $fares[$row['service']] = $row['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚚 SwiftTrans Logistics - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Animations & Styles */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s forwards;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .bg-overlay {
            background: rgba(0, 0, 0, 0.6);
        }
        .nav-link:hover {
            color: #60a5fa;
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex flex-col min-h-screen">

    <!-- Hero Section with Background Image -->
    <section class="relative h-screen w-full">
        <img src="frontend/assets/images/bg-3.jpg" alt="SwiftTrans Hero" class="absolute inset-0 w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-overlay flex flex-col justify-center items-center text-center">
            <h1 class="text-5xl font-extrabold mb-4 fade-in">🚚 Welcome to SwiftTrans Logistics</h1>
            <p class="text-xl text-gray-300 fade-in">Fast, Reliable & Affordable Intercity Goods Transport 💨</p>
            <a href="backend/auth/login.php" class="mt-8 px-6 py-3 bg-blue-500 text-white text-lg rounded-lg hover:bg-blue-600 transition-transform hover:scale-105 fade-in">📦 Get Started</a>
        </div>
    </section>

    <!-- Navbar -->
    <header class="fixed top-0 w-full bg-gray-800 shadow-md z-50">
        <div class="flex justify-between items-center p-5">
            <div class="flex items-center">
                <img src="frontend/assets/images/logo.png" alt="Logo" class="w-10 h-10 mr-3">
                <h1 class="text-2xl font-bold">SwiftTrans Logistics 🚛</h1>
            </div>
            <nav>
                <a href="index.php" class="nav-link mx-3 text-gray-300 hover:text-white transition-all">🏠 Home</a>
                <a href="#about" class="nav-link mx-3 text-gray-300 hover:text-white transition-all">ℹ️ About Us</a>
                <a href="backend/auth/login.php" class="nav-link mx-3 text-gray-300 hover:text-white transition-all">🔑 Login</a>
                <a href="backend/auth/signup.php" class="nav-link mx-3 text-gray-300 hover:text-white transition-all">📝 Register</a>
            </nav>
        </div>
    </header>

    <!-- Services Section (Dynamic Fares) -->
    <section class="py-20 bg-gray-900 text-center">
        <h2 class="text-4xl font-extrabold mb-10 fade-in">🚗 Our Transport Services</h2>
        <div class="flex justify-center gap-10 flex-wrap px-10">
            <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg w-80 hover:scale-105 transition-transform fade-in">
                <img src="frontend/assets/images/bike.png" alt="Bike" class="w-40 mx-auto">
                <h3 class="text-2xl font-bold mt-3">🏍️ Bike</h3>
                <p class="text-md text-gray-500">Lightweight loads (₹<?php echo $fares['Bike']; ?>/km)</p>
                <a href="backend/auth/login.php" class="block mt-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">🚀 Book Now</a>
            </div>

            <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg w-80 hover:scale-105 transition-transform fade-in">
                <img src="frontend/assets/images/tempo.png" alt="Small Tempo" class="w-40 mx-auto">
                <h3 class="text-2xl font-bold mt-3">🚚 Small Tempo</h3>
                <p class="text-md text-gray-500">Medium loads (₹<?php echo $fares['Small Tempo']; ?>/km)</p>
                <a href="backend/auth/login.php" class="block mt-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">📦 Book Now</a>
            </div>

            <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg w-80 hover:scale-105 transition-transform fade-in">
                <img src="frontend/assets/images/truck.png" alt="Truck" class="w-40 mx-auto">
                <h3 class="text-2xl font-bold mt-3">🚛 Truck</h3>
                <p class="text-md text-gray-500">Heavy loads (₹<?php echo $fares['Truck']; ?>/km)</p>
                <a href="backend/auth/login.php" class="block mt-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">📦 Book Now</a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-gray-800 text-center fade-in">
        <h2 class="text-4xl font-extrabold mb-10">📖 About SwiftTrans Logistics</h2>
        <p class="max-w-3xl mx-auto text-gray-300 text-lg leading-relaxed">
            🌐 At SwiftTrans Logistics, we believe in delivering not just goods, but trust and reliability. 
            With a modern fleet of bikes, tempos, and trucks, we provide safe and fast intercity transportation 
            for all types of loads. 🚀 Our mission is to make goods transportation simple, affordable, and hassle-free. 
            Whether it’s a lightweight parcel or heavy cargo, we ensure on-time delivery with complete transparency 
            and exceptional service. 💼
        </p>
    </section>

    <!-- Footer -->
    <footer class="mt-auto text-center p-5 bg-gray-800">
        <p>🌐 &copy; 2025 SwiftTrans Logistics. All Rights Reserved. 🚛</p>
    </footer>

</body>
</html>
