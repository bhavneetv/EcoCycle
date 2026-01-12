<?php
include_once "../config/conn.php";
if (!isset($_SESSION['User'])) {
    header("Location: ../login.php");
    exit();
} else {
    $user_id = $_SESSION['User'];
    $query = "SELECT * FROM users WHERE email = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $name = $user['full_name'];
    if ($user['role'] !== 'admin') {
        header("Location: ../login.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoCycle | Settings</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.7/dist/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
    <link rel="manifest" href="../assets/images/site.webmanifest">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 25%, #16213e 50%, #0f3460 100%);
            background-attachment: fixed;
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .glassmorphism-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.3);
        }

        .glassmorphism-sidebar {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 4px 0 20px 0 rgba(0, 0, 0, 0.3);
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card-green {
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.8) 0%, rgba(56, 239, 125, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card-purple {
            background: linear-gradient(135deg, rgba(139, 69, 219, 0.8) 0%, rgba(167, 139, 250, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card-orange {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.8) 0%, rgba(251, 113, 133, 0.8) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-item {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-item:hover::before {
            left: 100%;
        }

        .nav-item.active {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #10b981;
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .glow-effect {
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
            transition: box-shadow 0.3s ease;
        }

        .glow-effect:hover {
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.5);
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .shimmer {
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            background-size: 200% 200%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% -200%;
            }

            100% {
                background-position: 200% 200%;
            }
        }
    </style>
</head>

<body class="min-h-screen text-white">
    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobile-menu-btn" class="glassmorphism text-green-400 p-3 rounded-xl shadow-lg hover:bg-green-500/20 transition-all glow-effect">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 glassmorphism-sidebar sidebar-transition transform -translate-x-full lg:translate-x-0">
        <!-- Logo -->
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-white text-shadow">EcoCycle</h1>
            </div>
            <button id="close-sidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-6">
            <div class="px-4 space-y-2">
                <a href="index.php" class="nav-item active flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="dashboard">
                    <i class="fas fa-chart-bar text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="#" disabled class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="settings">
                    <i class="fas fa-cog text-lg"></i>
                    <span class="font-medium">Website Settings</span>
                </a>
                <a href="manageUsers.php" class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="users">
                    <i class="fas fa-users text-lg"></i>
                    <span class="font-medium">Manage Users</span>
                </a>
                <a href="requestBottles.php" class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="bottles">
                    <i class="fas fa-recycle text-lg"></i>
                    <span class="font-medium">Bottle Requests</span>
                </a>

            </div>
        </nav>

        <!-- User Info -->
        <a href="../profile.php">
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
                <div class="flex items-center space-x-3 glassmorphism p-3 rounded-xl">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white"><?php echo $name; ?></p>
                        <p class="text-xs text-gray-400"><?php echo $user['email']; ?></p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-70 z-30 hidden lg:hidden backdrop-blur-sm"></div>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Header -->
        <header class="glassmorphism border-b border-white/10 px-4 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="ml-12 lg:ml-0">
                    <h2 id="page-title" class="text-2xl font-bold text-white text-shadow">Dashboard</h2>
                    <p class="text-gray-300 mt-1">Welcome back! Here's what's happening with ecoCycle.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- <button class="relative p-2 text-gray-300 hover:text-white glassmorphism rounded-xl transition-all glow-effect">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center">3</span> -->
                    </button>
                    <!-- <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center glow-effect"> -->
                    <a href="../config/logout.php"><i class="fa-solid fa-right-from-bracket" style="color: #ff0000;"></i></a>
                    <!-- </div> -->
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-4 lg:p-8">




            <!-- Other Sections (Hidden by default) -->

            <div id="settings-section" class="">
                <div class="glassmorphism-card rounded-2xl shadow-2xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-6 text-shadow">Website Settings</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Site Name</label>
                            <input id="siteName" type="text" class="w-full px-3 py-2 glassmorphism border border-white/20 rounded-xl text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Contact Email</label>
                            <input id="contactEmail" type="email" class="w-full px-3 py-2 glassmorphism border border-white/20 rounded-xl text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Reward Points per Bottle</label>
                            <input id="rewardPerBottle" type="number" class="w-full px-3 py-2 glassmorphism border border-white/20 rounded-xl text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Minimum Collection Bottles</label>
                            <input id="minCollection" type="number" class="w-full px-3 py-2 glassmorphism border border-white/20 rounded-xl text-white " id="minCollection">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Points per Rupee</label>
                            <input id="pointsPerRupee" type="number" class="w-full px-3 py-2 glassmorphism border border-white/20 rounded-xl text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Bonus Points</label>
                            <input id="bonusPoints" type="number" class="w-full px-3 py-2 glassmorphism border border-white/20 rounded-xl text-white">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-xl hover:from-green-600 hover:to-green-700 transition-all glow-effect">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const closeSidebar = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        mobileMenuBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
        closeSidebar.addEventListener('click', toggleSidebar);



        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Set dashboard as active by default
            // navItems[0].classList.add('active');

            // Add some interactive effects
            const cards = document.querySelectorAll('.stat-card, .stat-card-green, .stat-card-purple, .stat-card-orange');
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>

    <script>
        async function loadSettings() {
            const res = await fetch("api/setting.php");
            const data = await res.json();

            document.querySelector("#settings-section input[type='text']").value = data.site_name;
            document.querySelector("#settings-section input[type='email']").value = data.contact_email;
            document.querySelector("#settings-section input[type='number']").value = data.reward_per_bottle;
            document.querySelector("#minCollection").value = data.min_collection_bottles;

            // optional new fields
            if (document.querySelector("#pointsPerRupee"))
                document.querySelector("#pointsPerRupee").value = data.points_per_rupee;
            if (document.querySelector("#bonusPoints"))
                document.querySelector("#bonusPoints").value = data.bonus_points;
        }

        async function saveSettings() {
            const payload = {
                site_name: document.querySelector("#settings-section input[type='text']").value,
                contact_email: document.querySelector("#settings-section input[type='email']").value,
                reward_per_bottle: document.querySelector("#settings-section input[type='number']").value,
                min_collection_bottles: document.querySelector("#minCollection").value,
                points_per_rupee: document.querySelector("#pointsPerRupee")?.value || 2,
                bonus_points: document.querySelector("#bonusPoints")?.value || 50
            };

            const res = await fetch("api/setting.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            });

            const result = await res.json();
            alert(result.message);
        }

        document.addEventListener("DOMContentLoaded", () => {
            loadSettings();
            document.querySelector("#settings-section button").addEventListener("click", saveSettings);
        });
    </script>
</body>

</html>