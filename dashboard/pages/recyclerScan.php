<!-- Main Content -->


<?php
include_once "../../config/conn.php";

if (!isset($_SESSION["User"])) {
   http_response_code(401);
   echo "UNAUTHORIZED";
   exit();
}
$user = $_SESSION["User"];
$sql = "SELECT user_id , role FROM users WHERE email = '$user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if ($row["role"] != "recycler") {
        http_response_code(401);
        echo "UNAUTHORIZED";
        exit();
    }
}
?>

<div id="blackScreen" class="blackScreen hidden" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 9999;">
    <div class="loader"></div>
</div>
<main class="pt-16 lg:pl-64 min-h-screen">


    <div class="p-4 md:p-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="glass rounded-2xl p-4 md:p-6 mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Recycling Request Scanner 🔍</h2>
            <p class="text-gray-300 text-sm md:text-base">Scan QR code or enter request ID manually to process recycling requests</p>
        </div>

        <!-- QR Scanner Section -->
        <div class="glass rounded-2xl p-4 md:p-6 mb-6" id="scanner-section">
            <h3 class="text-lg md:text-xl font-semibold text-white mb-4">QR Code Scanner</h3>

            <!-- Camera Container -->
            <div class="relative bg-black rounded-xl overflow-hidden mb-4" id="scanner-container">

            </div>

            <!-- Scanner Controls -->
            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                <button id="start-scanner" class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Start Scanner
                </button>
                <button id="stop-scanner" class="flex-1 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    Stop Scanner
                </button>
            </div>

            <!-- Scanner Status -->
            <div id="scanner-status" class="text-center p-3 rounded-lg bg-gray-800/50 text-gray-300 text-sm">
                Scanner ready. Click "Start Scanner" to begin.
            </div>
        </div>

        <!-- Manual Input Section -->
        <div class="glass rounded-2xl p-4 md:p-6 mb-6">
            <h3 class="text-lg md:text-xl font-semibold text-white mb-4">Manual Input (Fallback)</h3>
            <div class="flex flex-col sm:flex-row gap-3">
                <input
                    type="text"
                    id="manual-code"
                    placeholder="Enter request code manually..."
                    class="flex-1 bg-gray-800/50 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all duration-300">
                <button id="submit-manual" class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 whitespace-nowrap">
                    Submit Code
                </button>
            </div>
        </div>

        <!-- Request Details Section -->
        <div id="request-details" class="glass rounded-2xl p-4 md:p-6 mb-6 hidden">
            <!-- Recycler Information -->
            <div class="border-b border-gray-600 pb-4 md:pb-6 mb-4 md:mb-6">
                <h3 class="text-lg md:text-xl font-semibold text-white mb-4">Recycler Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-800/30 rounded-lg p-3 md:p-4">
                        <p class="text-gray-400 text-sm mb-1">Recycler ID</p>
                        <p class="text-white font-semibold text-sm md:text-base" id="recycler-id"></p>
                    </div>
                    <div class="bg-gray-800/30 rounded-lg p-3 md:p-4">
                        <p class="text-gray-400 text-sm mb-1">User Name</p>
                        <p class="text-white font-semibold text-sm md:text-base" id="recycler-name"></p>
                    </div>
                    <div class="bg-gray-800/30 rounded-lg p-3 md:p-4">
                        <p class="text-gray-400 text-sm mb-1">Contact</p>
                        <p class="text-white font-semibold text-sm md:text-base" id="recycler-contact"></p>
                    </div>
                    <div class="bg-gray-800/30 rounded-lg p-3 md:p-4">
                        <p class="text-gray-400 text-sm mb-1">Request Date</p>
                        <p class="text-white font-semibold text-sm md:text-base" id="request-date"></p>
                    </div>
                </div>
            </div>

            <!-- Bottle Details Table -->
            <div class="mb-4 md:mb-6">
                <h3 class="text-lg md:text-xl font-semibold text-white mb-4">Bottle Details</h3>

                <!-- Mobile Card Layout (hidden on desktop) -->
                <div class="block md:hidden space-y-3" id="bottle-details-bodym">



                    <!-- Mobile Total -->
                    <div class="bg-gray-800/30 rounded-lg p-4 border-t-2 border-gray-600">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300 font-semibold">Total:</span>
                            <div class="flex gap-4">
                                <span class="text-white font-semibold">Qty: 0</span>
                                <span class="text-green-400 font-semibold">0 pts</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table Layout (hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <div class="min-w-full bg-gray-800/20 rounded-lg">
                        <!-- Table Header -->
                        <div class="grid grid-cols-5 gap-4 p-4 border-b border-gray-600 text-sm font-medium text-gray-300">
                            <div>S.No</div>
                            <div>Bottle Name</div>
                            <div>Barcode</div>
                            <div>Quantity</div>
                            <div>Points</div>
                        </div>

                        <!-- Table Rows -->
                        <div id="bottle-details-body">

                        </div>

                        <!-- Table Footer -->
                        <div class="grid grid-cols-5 gap-4 p-4 border-t-2 border-gray-600 bg-gray-800/30 text-sm font-semibold">
                            <div class="text-gray-300 col-span-2">Total:</div>
                            <div></div>
                            <div class="text-white" id="totalItems"></div>
                            <div class="text-green-400" id="totalPoints"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
                <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-3 md:p-4 text-center">
                    <p class="text-green-400 text-lg md:text-2xl font-bold" id="totalItemsf">6</p>
                    <p class="text-green-300 text-xs md:text-sm">Total Items</p>
                </div>
                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-3 md:p-4 text-center">
                    <p class="text-yellow-400 text-lg md:text-2xl font-bold" id="totalPointsf">50</p>
                    <p class="text-yellow-300 text-xs md:text-sm">Total Points</p>
                </div>

                <div class="bg-purple-500/10 border border-purple-500/20 rounded-lg p-3 md:p-4 text-center">
                    <p class="text-purple-400 text-lg md:text-2xl font-bold" id="totalKgf">2.1kg</p>
                    <p class="text-purple-300 text-xs md:text-sm">CO₂ Saved</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button id="reject-request" class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 md:py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                    <!-- Default icon -->
                    <div class="spinner w-5 hidden h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" id="reject-spinner"></div>
                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <!-- Spinner (hidden by default) -->
                    <span class="button-text">Reject Request</span>
                </button>

                <button id="confirm-request" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 md:py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                    <!-- Default icon -->
                    <div class="spinner hidden w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" id="confirm-spinner"></div>
                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <!-- Spinner (hidden by default) -->
                    <span class="button-text">Confirm & Process</span>
                </button>
            </div>
        </div>

        <!-- Status Messages -->
        <div id="status-message" class="fixed top-20 right-4 max-w-sm z-50 hidden">
            <div class="glass rounded-lg p-4 border-l-4 border-green-500">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-white text-sm" id="status-text">QR Code scanned successfully!</span>
                </div>
            </div>
        </div>
    </div>

    <script>

    </script>
</main>