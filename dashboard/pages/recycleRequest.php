<!-- Main Content -->

<?php
include_once "../../config/conn.php";

if (!isset($_SESSION["User"])) {
    header("Location: ../../login.php");
    exit;
}
$user = $_SESSION["User"];
$sql = "SELECT user_id , role FROM users WHERE email = '$user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if ($row["role"] != "recycler") {
        header("Location: ../../login.php");
        exit;
    }
}
?>
<main class="pt-16 lg:pl-64 min-h-screen">
    <div class="p-6">
        <!-- Header Card -->
        <div class="glass rounded-2xl p-6 mb-6 card-hover">
            <h2 class="text-2xl font-bold text-white mb-2">Recycling Requests 📋</h2>
            <p class="text-gray-300">Manage and accept recycling requests from your area</p>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Pending Requests</p>
                        <p class="text-3xl font-bold text-white" id="totalRequests">0</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Bottles</p>
                        <p class="text-3xl font-bold text-white" id="totalBottles">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Your Area</p>
                        <p class="text-3xl font-bold text-white" id="pincode">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="glass rounded-2xl p-6 card-hover">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-white mb-2">Pending Requests Based on Your Pincode</h3>
                <p class="text-gray-400 text-sm">Review and accept recycling requests from users in your service area (<span id="pincodex">0</span>)</p>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">User Name</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Phone No</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Address</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Bottles</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Pincode</th>
                            <th class="text-center py-3 px-4 text-gray-300 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody id="requestsTableBody">



                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4" id="requestsTableMobile">


            </div>

            <div id="emptyState" class="text-center py-12 hidden">
                <div class="w-24 h-24 bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Pending Requests</h3>
                <p class="text-gray-400">All requests in your area have been processed!</p>
            </div>
        </div>
    </div>
</main>

<script>
    // Accept request function
    
</script>