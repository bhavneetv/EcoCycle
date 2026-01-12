<!-- Main Content -->
<?php include "../../config/conn.php";


if (!isset($_SESSION['User'])) {

    $fullname = "Guest";
    $user_id = "Guest";
    $reward_points = "0";
    $co2_saved = "0";
    $wallet = "0";
    $total_bottles = "0";
    $streak_count = "0";
    $b = "0";
    $redeemed_points = "0";
    $sql = "SELECT * FROM settings WHERE setting_key = 'points_per_rupee'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $points_per_rupee = $row['setting_value'];
} else {


    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $fullname = $row['full_name'];
    $user_id = $row['user_id'];
    $reward_points = $row['total_points'];
    $redeemed_points = $row['redeemed_points'];

    $b = $row['bottleBeforeRedeem'];
    $co2_saved = round($row['carbonFree']) / 1000;
    
    $streak_count = $row['streak_count'];
    $sql = "SELECT * FROM settings WHERE setting_key = 'points_per_rupee'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $points_per_rupee = $row['setting_value'];
    echo "<script>console.log('asf');</script>";
    
}





?>
<main class="pt-16 lg:pl-64 min-h-screen">
    <!-- QR Code Popup Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="glass rounded-2xl p-8 max-w-md w-full mx-4 relative">
            <!-- Close Button -->
            <button id="closeQrModal" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500/20 to-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Redemption QR Code</h3>
                <p class="text-gray-300 text-sm">Present this QR code to confirm your redemption request</p>
            </div>

            <!-- QR Code Display Area -->
            <div class="bg-white p-6 rounded-xl mb-6 flex items-center justify-center">
                <canvas id="qrcode" class="mx-auto"></canvas>
            </div>

            <!-- Instructions -->
            <div class="text-center">
                <p class="text-yellow-400 font-semibold mb-2">⚠️ Save this QR code to confirm the request</p>
                <p class="text-gray-400 text-sm mb-6">Screenshot or save this QR code. You'll need to show it to complete your redemption.</p>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button id="downloadQr" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download
                    </button>
                    <button id="confirmRedemption" class="flex-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="p-6">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent mb-2">
                My Rewards
            </h1>
            <p class="text-gray-300">Track your recycling rewards and achievements</p>
        </div>

        <!-- Rewards Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-8">
            <!-- Total Rewards Card -->
            <div class="glass rounded-2xl p-6 card-hover pulse-glow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-gray-400 text-sm">Total Rewards Earned</p>
                        <p id="totalPoints" class="text-4xl font-bold text-white counter-animation"><?php echo  $reward_points; ?></p>
                        <p class="text-blue-400 text-sm mt-1">Total: ₹<span id="redeemedValue"> <?php echo $reward_points * 0.25; ?></span> value</p>

                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500/20 to-blue-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>

            </div>

            <!-- Redeemed Rewards Card -->
            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Redeemed</p>
                        <p id="redeemedPoints" class="text-3xl font-bold text-white counter-animation"><?php echo $redeemed_points; ?></p>
                        <p class="text-blue-400 text-sm mt-1">₹<span id="redeemedValue"><?php echo $redeemed_points * $points_per_rupee; ?></span> value</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Balance Card -->
            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-gray-400 text-sm">Available Balance</p>
                        <p id="availablePoints" class="text-3xl font-bold text-white counter-animation"><?php echo $reward_points - $redeemed_points; ?></p>
                        <p class="text-blue-400 text-sm mt-1">₹<span id="redeemedValue"><?php echo round(($reward_points - $redeemed_points) * $points_per_rupee, 2); ?></span> value</p>
                        <p class="text-blue-400 text-sm mt-1" id="bottleBeforeRedeemt"> <?php echo $b; ?> Bottles to be collected</p>
                        <p class="text-yellow-400 text-sm mt-1" id="redeemText">Ready to redeem</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <button id="redeemBtn"
                    class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                    <svg id="btnLoaderR" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    <span id="btnText">Redeem Now</span>
                </button>

            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="glass rounded-2xl p-6 mb-8 card-hover">
            <h3 class="text-xl font-semibold text-white mb-6">Recent Activity</h3>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Date</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Recycler Name</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Total Bottles</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Points deducted</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Status</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody id="activityTable">

                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-2" id="mobileActivityCards">

            </div>
        </div>

        <!-- QR Code Modal -->
        <div id="qrModaltable" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="glass rounded-2xl p-6 max-w-sm w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">QR Code</h3>
                    <button onclick="closeModalTable()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <canvas id="qrcodePop" class="bg-white p-4 rounded-lg mb-4 inline-block"></canvas>
                    <p class="text-gray-300 text-sm">Unique Code: <span id="codeText" class="text-white font-mono bg-gray-800 px-2 py-1 rounded"></span></p>
                </div>
            </div>
        </div>

        <!-- Redeemed Code Modal -->
        <div id="redeemedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="glass rounded-2xl p-6 max-w-sm w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">Redeemed Code</h3>
                    <button onclick="closeRedeemedModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <div class="bg-green-900/30 border border-green-500 rounded-lg p-4 mb-4">
                        <svg class="w-12 h-12 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-400 font-semibold">Code Redeemed</p>
                    </div>
                    <p class="text-gray-300 text-sm">Code: <span id="redeemedCodeText" class="text-white font-mono bg-gray-800 px-2 py-1 rounded"></span></p>
                </div>
            </div>
        </div>

        <style>
            .action-btn {
                transition: all 0.2s ease;
            }

            .action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }

            .glass {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .mobile-card {
                background: rgba(255, 255, 255, 0.05);
                transition: all 0.2s ease;
            }

            .mobile-card:hover {
                background: rgba(255, 255, 255, 0.08);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            /* Status styles */
            .status-pending {
                background: rgba(255, 193, 7, 0.2);
                color: #ffc107;
            }

            .status-completed {
                background: rgba(40, 167, 69, 0.2);
                color: #28a745;
            }

            .status-redeemed {
                background: rgba(108, 117, 125, 0.2);
                color: #6c757d;
            }

            /* Responsive breakpoint adjustments */
            @media (max-width: 768px) {
                .glass {
                    padding: 1rem;
                }

                h3 {
                    font-size: 1.125rem;
                }

                .mobile-card {
                    margin-bottom: 0.75rem;
                }

                .action-btn {
                    padding: 0.5rem;
                }

                .action-btn svg {
                    width: 1rem;
                    height: 1rem;
                }
            }

            @media (max-width: 480px) {
                .glass {
                    padding: 0.75rem;
                }

                .mobile-card {
                    padding: 0.75rem;
                }

                .grid-cols-2 {
                    grid-template-columns: 1fr;
                    gap: 0.5rem;
                }

                .action-btn {
                    padding: 0.375rem;
                }
            }

            /* Modal responsive adjustments */
            @media (max-width: 640px) {
                .glass.rounded-2xl {
                    margin: 1rem;
                    max-width: calc(100vw - 2rem);
                }

                #qrcode canvas {
                    max-width: 100%;
                    height: auto;
                }
            }
        </style>

        <script>
        </script>

        <!-- Achievements Section -->
        <div class="glass rounded-2xl p-6 card-hover">
            <h3 class="text-xl font-semibold text-white mb-6">Achievements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Plastic Saver Badge -->
                <div class="glass rounded-xl p-4 text-center card-hover badge-glow">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Plastic Saver</h4>
                    <p class="text-gray-400 text-sm mb-2">Recycle 100+ bottles</p>
                    <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs" id="plasticSaver">0/100</span>
                </div>

                <!-- Eco Hero Badge -->
                <div class="glass rounded-xl p-4 text-center card-hover badge-glow">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Eco Hero</h4>
                    <p class="text-gray-400 text-sm mb-2">Save 10kg+ CO₂</p>
                    <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs" id="ecoHero">0/100</span>
                </div>

                <!-- Top Recycler Badge -->
                <div class="glass rounded-xl p-4 text-center card-hover achievement-locked">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Top Recycler</h4>
                    <p class="text-gray-400 text-sm mb-2">Recycle 500+ bottles</p>
                    <span class="bg-gray-600/40 text-gray-400 px-3 py-1 rounded-full text-xs " id="topRecycler">0/500</span>
                </div>

                <!-- Weekly Champion Badge -->
                <div class="glass rounded-xl p-4 text-center card-hover badge-glow">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Weekly Champion</h4>
                    <p class="text-gray-400 text-sm mb-2">Top recycler this week</p>
                    <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs" id="weeklyChampion">0</span>
                </div>

                <!-- Streak Master Badge -->
                <div class="glass rounded-xl p-4 text-center card-hover achievement-locked">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500/20 to-pink-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        </svg>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Streak Master</h4>
                    <p class="text-gray-400 text-sm mb-2">30-day recycle streak</p>
                    <span class="bg-gray-600/40 text-gray-400 px-3 p/30y-1 rounded-full text-xs" id="streakMaster">0</span>
                </div>

                <!-- Community Leader Badge -->

            </div>
        </div>
    </div>

    <script>
        // Sample reward data - replace with actual API call
    </script>
</main>