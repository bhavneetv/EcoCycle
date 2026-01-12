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
    <title>EcoCycle | Manage Users</title>
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
                <a href="#" disabled class="nav-item flex items-center space-x-3 px-4 py-3 text-gray-300 rounded-xl hover:text-green-400 transition-all active" data-section="users">
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
                    <h2 id="page-title" class="text-2xl font-bold text-white text-shadow">Manage Users</h2>
                    <p class="text-gray-300 mt-1">Welcome back!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- <button class="relative p-2 text-gray-300 hover:text-white glassmorphism rounded-xl transition-all glow-effect">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center">3</span> -->
                    </button>
                    <!-- <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center glow-effect"> -->
                    <a href="../config/logout.php"> <i class="fa-solid fa-right-from-bracket" style="color: #ff0000;"></i></a>
                    <!-- </div> -->
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-4 lg:p-8">
            <div id="users-section" class="">
                <div class="glassmorphism-card rounded-2xl shadow-2xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-4 text-shadow">User Management</h3>
                    <p class="text-gray-300 mb-6">Manage all registered users and their activities.</p>

                    <!-- Filters Section -->
                    <div class="bg-white/5 rounded-xl p-4 mb-6 border border-white/10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <input type="text" placeholder="Search by name or email ..." class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400/50">
                                <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <div>
                                <select class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400/50">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <!-- <option value="suspended">Suspended</option> -->
                                </select>
                            </div>
                            <div>
                                <select class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400/50">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="recycler">Recycler</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="glassmorphism">
                                <tr>
                                    <th class="px-4 py-3 text-gray-300 font-medium">Name</th>
                                    <th class="px-4 py-3 text-gray-300 font-medium">Email</th>
                                    <th class="px-4 py-3 text-gray-300 font-medium">Role</th>
                                    <th class="px-4 py-3 text-gray-300 font-medium">Bottles Recycled</th>
                                    <th class="px-4 py-3 text-gray-300 font-medium">Status</th>
                                    <th class="px-4 py-3 text-gray-300 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-4 py-3 font-medium text-white">Alice Johnson</td>
                                    <td class="px-4 py-3 text-gray-300">alice@example.com</td>
                                    <td class="px-4 py-3">
                                        <span class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm border border-purple-500/30">Admin</span>
                                    </td>
                                    <td class="px-4 py-3 text-white">87</td>
                                    <td class="px-4 py-3">
                                        <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm border border-green-500/30">Active</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <button class="bg-blue-500/20 text-blue-300 hover:bg-blue-500/30 px-3 py-1 rounded-lg text-sm border border-blue-500/30 transition-all">Edit</button>
                                            <div class="relative group">
                                                <button class="bg-amber-500/20 text-amber-300 hover:bg-amber-500/30 px-3 py-1 rounded-lg text-sm border border-amber-500/30 transition-all">Role</button>
                                                <div class="absolute top-full left-0 mt-1 bg-gray-800 border border-white/20 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 min-w-32">
                                                    <button class="block w-full text-left px-3 py-2 text-sm text-white hover:bg-white/10 rounded-t-lg">Admin</button>
                                                    <button class="block w-full text-left px-3 py-2 text-sm text-white hover:bg-white/10">User</button>
                                                    <button class="block w-full text-left px-3 py-2 text-sm text-white hover:bg-white/10 rounded-b-lg">Moderator</button>
                                                </div>
                                            </div>
                                            <button class="bg-red-500/20 text-red-300 hover:bg-red-500/30 px-3 py-1 rounded-lg text-sm border border-red-500/30 transition-all">Delete</button>
                                        </div>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Section -->
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-white/10">
                        <div class="text-sm text-gray-400">
                            Showing <span class="text-white font-medium">1-5</span> of <span class="text-white font-medium">25</span> users
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 bg-white/10 text-gray-400 rounded-lg border border-white/20 hover:bg-white/20 transition-all cursor-not-allowed" disabled>
                                Previous
                            </button>

                            <button class="px-3 py-1 bg-white/10 text-white rounded-lg border border-white/20 hover:bg-white/20 transition-all">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <style>
            .glassmorphism-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .glassmorphism {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(5px);
            }

            .text-shadow {
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }
        </style>
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
        /* ========= CONFIG ========= */

        const API_URL = "api/fetch_users.php"; // adjust path if needed
        let currentPage = 1;
        const limit = 5; // items per page
        let totalUsers = 0;
        let currentSearch = "";
        let currentStatus = "";
        let currentRole = "";
        let searchDebounceTimer = null;

        /* ======== FETCH & RENDER ======== */
        async function fetchUsers() {
            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    limit,
                    search: currentSearch,
                    status: currentStatus,
                    role: currentRole
                });
                const res = await fetch(`${API_URL}?${params.toString()}`);
                if (!res.ok) throw new Error("Network response not ok");
                const json = await res.json();
                totalUsers = Number(json.total) || 0;
                renderTable(json.data || []);
                renderPagination(Number(json.page) || currentPage, Number(json.limit) || limit, totalUsers);
            } catch (err) {
                console.error("fetchUsers error:", err);
            }
        }

        function renderTable(users) {
            const tbody = document.querySelector("tbody");
            if (!tbody) {
                console.warn("tbody not found in DOM");
                return;
            }

            if (!users.length) {
                tbody.innerHTML = `
      <tr>
        <td colspan="6" class="px-4 py-6 text-center text-gray-400">No users found</td>
      </tr>`;
                return;
            }

            tbody.innerHTML = users.map(u => `
    <tr class="hover:bg-white/5 transition-colors">
      <td class="px-4 py-3 font-medium text-white">${escapeHtml(u.full_name || "—")}</td>
      <td class="px-4 py-3 text-gray-300">${escapeHtml(u.email || "—")}</td>
      <td class="px-4 py-3">
        <span class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm border border-purple-500/30">
          ${escapeHtml(u.role || "—")}
        </span>
      </td>
      <td class="px-4 py-3 text-white">${escapeHtml(u.total_points ?? "0")}</td>
      <td class="px-4 py-3">
        <span class="${u.status === 'active' ? 'bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm border border-green-500/30' : 'bg-red-500/20 text-red-300 px-3 py-1 rounded-full text-sm border border-red-500/30'}">
          ${escapeHtml(u.status || "—")}
        </span>
      </td>
      <td class="px-4 py-3">
        <div class="flex items-center gap-2">
          <button class="bg-blue-500/20 text-blue-300 px-3 py-1 rounded-lg text-sm border border-blue-500/30" onclick="changeRole(${u.user_id})">Edit</button>
          <button class="bg-red-500/20 text-red-300 px-3 py-1 rounded-lg text-sm border border-red-500/30" onclick="deleteUser(${u.user_id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join("");
        }

        /* ======== SAFE HTML ESCAPE ======== */
        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;");
        }

        /* ======== PAGINATION HELPERS ======== */
        function findPaginationButtons() {
            // 1) find by text content
            const buttons = Array.from(document.querySelectorAll("button"));
            let prev = buttons.find(b => b.textContent.trim().toLowerCase().includes("previous"));
            let next = buttons.find(b => b.textContent.trim().toLowerCase().includes("next"));

            // 2) fallback: find a container that likely holds them (search for the text 'Previous'/'Next')
            if (!prev || !next) {
                const pagerContainers = Array.from(document.querySelectorAll("div")).filter(d => d.textContent && (d.textContent.includes("Previous") || d.textContent.includes("Next")));
                for (const c of pagerContainers) {
                    const btns = Array.from(c.querySelectorAll("button"));
                    if (btns.length >= 2) {
                        prev = prev || btns[0];
                        next = next || btns[1];
                        break;
                    }
                }
            }

            // 3) final fallback: use the last two buttons on the page
            if (!prev || !next) {
                const all = Array.from(document.querySelectorAll("button"));
                if (all.length >= 2) {
                    prev = prev || all[all.length - 2];
                    next = next || all[all.length - 1];
                }
            }

            return {
                prev,
                next
            };
        }

        function toggleDisabledStyles(btn, disabled) {
            if (!btn) return;
            if (disabled) {
                btn.disabled = true;
                btn.classList.add("cursor-not-allowed");
                btn.classList.add("opacity-60");
            } else {
                btn.disabled = false;
                btn.classList.remove("cursor-not-allowed");
                btn.classList.remove("opacity-60");
            }
        }

        function renderPagination(page, limitVal, total) {
            // info text element (several fallbacks)
            const infoEl = document.querySelector(".text-sm.text-gray-400") || document.querySelector(".text-sm");
            const showingFrom = total === 0 ? 0 : (page - 1) * limitVal + 1;
            const showingTo = Math.min(page * limitVal, total);
            if (infoEl) {
                infoEl.innerHTML = `Showing <span class="text-white font-medium">${showingFrom}-${showingTo}</span> of <span class="text-white font-medium">${total}</span> users`;
            }

            const {
                prev,
                next
            } = findPaginationButtons();
            if (!prev || !next) {
                console.warn("Pagination buttons not found. Please ensure your page has the Previous/Next buttons or share the full HTML.");
                return;
            }

            const atFirst = page <= 1;
            const atEnd = page * limitVal >= total;

            toggleDisabledStyles(prev, atFirst);
            toggleDisabledStyles(next, atEnd);

            // remove previous handlers and assign new ones
            prev.onclick = () => {
                if (!prev.disabled) {
                    currentPage = Math.max(1, page - 1);
                    fetchUsers();
                }
            };
            next.onclick = () => {
                if (!next.disabled) {
                    currentPage = page + 1;
                    fetchUsers();
                }
            };
        }

        /* ======== UI BINDINGS ======== */
        document.addEventListener("DOMContentLoaded", () => {
            // search input (debounced)
            const searchInput = document.querySelector("input[placeholder*='Search by name']") || document.querySelector("input[type='text']");
            if (searchInput) {
                searchInput.addEventListener("input", (e) => {
                    clearTimeout(searchDebounceTimer);
                    searchDebounceTimer = setTimeout(() => {
                        currentSearch = e.target.value.trim();
                        currentPage = 1;
                        fetchUsers();
                    }, 300);
                });
            }

            // selects (status, role) — fallback tolerant
            const selects = Array.from(document.querySelectorAll("select"));
            if (selects.length >= 1) {
                selects[0].addEventListener("change", (e) => {
                    currentStatus = e.target.value;
                    currentPage = 1;
                    fetchUsers();
                });
            }
            if (selects.length >= 2) {
                selects[1].addEventListener("change", (e) => {
                    currentRole = e.target.value;
                    currentPage = 1;
                    fetchUsers();
                });
            }

            // initial load
            fetchUsers();
        });
        // Fetch users


        // Change role
        async function changeRole(id) {
            const newRole = prompt("Enter new role (admin, user, recycler):");
            if (!newRole) return;

            const res = await fetch("api/manageUsers.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=changeRole&id=${id}&role=${newRole}`
            });
            console.log(res);
            console.log(await res.json());
            fetchUsers();
        }

        // Delete user
        async function deleteUser(id) {
            if (!confirm("Are you sure you want to delete this account?")) return;

            const res = await fetch("api/manageUsers.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=delete&id=${id}`
            });
            console.log(await res.json());
            fetchUsers();
        }
    </script>

    </script>

</body>

</html>