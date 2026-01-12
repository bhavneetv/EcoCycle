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
    <title>EcoCycle Admin Dashboard - Bottle Requests</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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

        .table-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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

        .btn-confirm {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.8) 0%, rgba(16, 185, 129, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: linear-gradient(135deg, rgba(34, 197, 94, 1) 0%, rgba(16, 185, 129, 1) 100%);
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.4);
        }

        .btn-deny {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.8) 0%, rgba(220, 38, 38, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }

        .btn-deny:hover {
            background: linear-gradient(135deg, rgba(239, 68, 68, 1) 0%, rgba(220, 38, 38, 1) 100%);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
        }

        .btn-view {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8) 0%, rgba(37, 99, 235, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 1) 0%, rgba(37, 99, 235, 1) 100%);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
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

        .modal-overlay {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }

        .status-pending {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.3) 0%, rgba(245, 158, 11, 0.3) 100%);
            border: 1px solid rgba(251, 191, 36, 0.5);
            color: #fbbf24;
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }

            .mobile-card {
                display: block !important;
            }

            .desktop-table {
                display: none !important;
            }
        }

        @media (min-width: 769px) {
            .mobile-card {
                display: none !important;
            }

            .desktop-table {
                display: table !important;
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
                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-white text-shadow">ecoCycle</h1>
            </div>
            <button id="close-sidebar" class="lg:hidden text-gray-300 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-6">
            <div class="px-4 space-y-2">
                <a href="index.php" class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="dashboard">
                    <i class="fas fa-chart-bar text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="Settings.php" class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="settings">
                    <i class="fas fa-cog text-lg"></i>
                    <span class="font-medium">Website Settings</span>
                </a>
                <a href="manageUsers.php" class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="users">
                    <i class="fas fa-users text-lg"></i>
                    <span class="font-medium">Manage Users</span>
                </a>
                <a href="#" disabled class="nav-item active flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all" data-section="bottles">
                    <i class="fas fa-recycle text-lg"></i>
                    <span class="font-medium">Bottle Requests</span>
                </a>
            </div>
        </nav>

        <!-- User Info -->
        <a href="../login.php">
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
                    <h2 class="text-2xl font-bold text-white text-shadow">Bottle Requests</h2>
                    <p class="text-gray-300 mt-1">Manage and review pending bottle collection requests.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="glassmorphism px-4 py-2 rounded-xl">
                        <span class="text-sm text-gray-300">Pending: </span>
                        <span class="text-lg font-bold text-yellow-400" id="pending-count">5</span>
                    </div>
                    <i class="fa-solid fa-right-from-bracket text-red-500 cursor-pointer hover:text-red-400 transition-colors"></i>
                </div>
            </div>
        </header>

        <!-- Bottle Requests Content -->
        <main class="p-4 lg:p-8">
            <div class="glassmorphism-card rounded-2xl shadow-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white mb-4 sm:mb-0 text-shadow">Pending Bottle Requests</h3>
                    <div class="flex items-center space-x-3">
                        <div class="glassmorphism px-3 py-2 rounded-lg">
                            <i class="fas fa-search text-gray-400 mr-2"></i>
                            <input type="text" placeholder="Search requests..." class="bg-transparent text-white placeholder-gray-400 outline-none text-sm" id="search-input">
                        </div>
                        <select class="glassmorphism bg-transparent text-white px-3 py-2 rounded-lg outline-none text-sm" id="filter-select">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="denied">Denied</option>
                        </select>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="desktop-table table-container overflow-x-auto" style="width: 100%;">
                    <table class="w-full table-glass rounded-xl overflow-hidden">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left p-4 font-medium text-gray-300">Bottle Info</th>
                                <th class="text-left p-4 font-medium text-gray-300">User Details</th>
                                <th class="text-left p-4 font-medium text-gray-300">Quantity</th>
                                <th class="text-left p-4 font-medium text-gray-300">Scan Date</th>
                                <th class="text-left p-4 font-medium text-gray-300">Status</th>
                                <th class="text-center p-4 font-medium text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requests-table-body">
                            <!-- Table rows will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-card space-y-4" id="mobile-cards">
                    <!-- Mobile cards will be populated by JavaScript -->
                </div>
            </div>
        </main>
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 z-50 hidden modal-overlay flex items-center justify-center p-4">
        <div class="glassmorphism-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-white">Bottle Image</h3>
                    <button id="close-modal" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="text-center">
                    <img id="modal-image" src="" alt="Bottle Image" class="max-w-full h-auto rounded-xl shadow-lg">
                </div>
                <div class="mt-4 p-4 glassmorphism rounded-xl">
                    <p class="text-gray-300 text-sm" id="image-details"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/requestBottle.js"></script>
</body>

</html>