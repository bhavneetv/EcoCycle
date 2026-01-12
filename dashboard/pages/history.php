<!-- Main Content
<?php
include "../../config/conn.php";
// session_start();
if (!isset($_SESSION['User'])) {
   
    $reward_points = "0";
    $total_bottles = "0";
    $total_pending = "0";
    $total_processing = "0";
    $total_successPercent = "0";
    $item = "Bottles Details";
    $barcode =  "Barcode";
} else {

    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $role = $row['role'];

    $reward_points = $row['total_points'];
    if($role === "user"){
        $item = "Bottles Details";
        $barcode = "Barcode";
        $sql = "SELECT COUNT(*) AS total_bottles FROM scans WHERE user_id = $user_id";
    }
    else{
        $item = "User Name";
        $barcode = "Quantity / bottles";
        $sql = "SELECT COUNT(*) AS total_bottles FROM points WHERE recycler_id = $user_id";
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_bottles = $row['total_bottles'];
     
    if($role === "user"){
        $sql = "SELECT COUNT(*) AS totalPending FROM points WHERE user_id = $user_id AND status = 'Processing'";
    }
    else{
        $sql = "SELECT COUNT(*) AS totalPending FROM points WHERE recycler_id = $user_id  AND status = 'Accept by recycler'";
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_pending = $row['totalPending'];
    

    if($role === "user"){
        $sql = "SELECT COUNT(*) AS totalProcessing FROM points WHERE user_id = $user_id ";
    }
    else{
        $sql = "SELECT COUNT(*) AS totalProcessing FROM points WHERE recycler_id = $user_id AND status != 'Reject'";
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_processing = $row['totalProcessing'];

    if($total_processing > 0){
        $total_successPercent = round(($total_processing - $total_pending) / $total_processing * 100);
    }
    else{
        $total_successPercent = 0;
    }


}
?> -->
<main class="pt-16 lg:pl-64 min-h-screen">
    <div class="p-6">
        <!-- Page Header -->
        <div class="glass rounded-2xl p-6 mb-6">
            <h1 class="text-3xl font-bold text-white mb-2">Recycling History</h1>
            <p class="text-gray-300">Track all your bottle scanning activities and rewards</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="glass rounded-2xl p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="seach" id="searchInput" placeholder="Search by bottle name or code..." 
                               class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="lg:w-48">
                    <select id="statusFilter" class="w-full py-3 px-4 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="lg:w-48">
                    <select id="dateFilter" class="w-full py-3 px-4 border bg-white/10 border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all" class="bg-white/40">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="3months">Last 3 Months</option>
                    </select>
                </div>

                <!-- Clear Filters Button -->
                <button id="clearFilters" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="glass rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Scans</p>
                        <p class="text-2xl font-bold text-white" id="totalScans"><?php echo $total_bottles; ?></p>
                    </div>
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Points</p>
                        <p class="text-2xl font-bold text-white" id="totalPoints"><?php echo $reward_points; ?></p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Success Rate</p>
                        <p class="text-2xl font-bold text-white" id="successRate"><?php echo $total_successPercent; ?>%</p>
                    </div>
                    <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table/Cards -->
        <div class="glass rounded-2xl overflow-hidden">
            <!-- Table Header -->
            <div class="p-6 border-b border-white/10">
                <h3 class="text-xl font-semibold text-white">Scan History</h3>
                <p class="text-gray-400 text-sm mt-1">Your recent recycling activities</p>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium"><?php echo $item; ?></th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium"><?php echo $barcode; ?></th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Points</th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Date & Time</th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                       
                        <!-- Dynamic content will be inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden" id="historyCards">
                <!-- Dynamic content will be inserted here -->
        
    
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.462-.898-6.042-2.373C5.369 11.674 5 10.82 5 9.895v-.338C5 8.033 5.895 7 7.042 7h9.916C18.105 7 19 8.033 19 9.557v.338c0 .925-.369 1.779-.958 2.332z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-400 mb-2">No records found</h3>
                <p class="text-gray-500">Try adjusting your search or filter criteria</p>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="hidden text-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                <p class="text-gray-400">Loading history...</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6">
            <p class="text-gray-400 text-sm " id="showingRecords">
                Showing <span id="showingStart">1</span>-<span id="showingEnd">1</span> of <span id="totalRecords">0</span> records
            </p>
            <div class="flex gap-2">
                <button id="prevPage" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <button id="nextPage" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>
</main>

