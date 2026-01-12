<?php
include "../../config/conn.php";

if (!isset($_SESSION['User'])) {
    http_response_code(401); // Unauthorized
    echo "UNAUTHORIZED";
    exit();
}
 else {
    // Get recycler info
    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $fullname = $row['full_name'];
    $user_id = $row['user_id'];
    if ($row['role'] != 'recycler') {
        http_response_code(401); // Unauthorized
        exit();
    }

    // Get total bottles collected by this recycler
    $sql = "SELECT SUM(totalBottles) AS totalBottles FROM points WHERE recycler_id = $user_id AND status = 'Confirm'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_bottles_collected = $row['totalBottles'];

    // Get total CO2 saved (assuming each bottle saves ~50g of CO2)
    $total_co2_saved = round(($total_bottles_collected * 0.82), 2); // 50g per bottle in kg

    // Get total earnings (recyclers get ₹2 per bottle processed)
    $total_earnings = $total_bottles_collected * 0.25;

    // Get total unique users served
    $sql = "SELECT COUNT(DISTINCT user_id) AS unique_users FROM points WHERE recycler_id = $user_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_users_served = $row['unique_users'];

    // Calculate completion rate
    $sql = "SELECT 
                COUNT(CASE WHEN status != 'Reject' THEN 1 ELSE 0 END)  AS  total_scans,
                SUM(CASE WHEN status = 'Confirm' THEN 1 ELSE 0 END) AS completed_scans
                FROM points WHERE recycler_id = $user_id AND status != 'Reject'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    $completion_rate = $row['total_scans'] > 0 ? round(($row['completed_scans'] / $row['total_scans']) * 100, 1) : 0;
}
?>

<main class="pt-16 lg:pl-64 min-h-screen">
    <div class="p-6">
        <!-- Welcome Card -->
        <div class="glass rounded-2xl p-6 mb-6 card-hover relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-400/20 to-green-400/20 rounded-full blur-xl"></div>

            <!-- Main content -->
            <div class="relative z-10">
                <!-- Header with name -->
                <h2 class="text-2xl font-bold text-white mb-4">Welcome, <?php echo $fullname; ?> ♻️</h2>

                <!-- Completion Rate section - prominently displayed -->
                <div class="bg-gradient-to-r from-blue-500/20 to-cyan-500/20 border border-blue-400/30 rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-300 text-sm font-medium uppercase tracking-wide">Completion Rate</p>
                            <p class="text-3xl font-black text-blue-400 mt-1">
                                <?php echo $completion_rate; ?>
                                <span class="text-lg font-semibold text-blue-300 ml-1">%</span>
                            </p>
                        </div>
                        <div class="text-4xl">📊</div>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-gray-300 text-base leading-relaxed">
                    Manage recycling operations & help save the planet! 🌍
                </p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Bottles Collected</p>
                        <p class="text-3xl font-bold text-white"><?php echo $total_bottles_collected; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Earnings</p>
                        <p class="text-3xl font-bold text-white">₹<?php echo $total_earnings; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Users Served</p>
                        <p class="text-3xl font-bold text-white"><?php echo $total_users_served; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">CO₂ Saved</p>
                        <p class="text-3xl font-bold text-white"><?php echo $total_co2_saved; ?>kg</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass rounded-2xl p-6 card-hover">
                <h3 class="text-xl font-semibold text-white mb-4">Process Bottles</h3>
                <p class="text-gray-300 mb-6">Process pending bottle collections from users</p>
                <button id="processPendingBtn" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                    View Pending Collections
                </button>
            </div>

            <div class="glass rounded-2xl p-6 card-hover">
                <h3 class="text-xl font-semibold text-white mb-4">Collection Routes</h3>
                <p class="text-gray-300 mb-6">Manage your collection routes and schedules</p>
                <button id="manageRoutesBtn" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                    Manage Routes
                </button>
            </div>
        </div>

        <!-- Recent Collections -->
        <div class="glass rounded-2xl p-6 card-hover">
            <h3 class="text-xl font-semibold text-white mb-4">Recent Collections</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Date</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">User</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Bottle Type</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Quantity</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Status</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($user_id === "Guest") {
                            echo "<tr class='border-b border-gray-700/50 hover:bg-white/5 transition-colors'>";
                            echo "<td class='py-3 px-4 text-gray-300' colspan='6'>No collections found</td>";
                            echo "</tr>";
                        } else {
                           
                            $sql = "SELECT 
            p.id AS scan_id,                             
            p.created_at AS scanned_at,                    
            p.status,
            p.totalBottles,
            u.full_name AS user_name,
            CONCAT('Reward Redeemed (', p.totalBottles, ' bottles)') AS bottle_name
        FROM points AS p
        INNER JOIN users AS u ON u.user_id = p.user_id
        WHERE p.recycler_id = ?
        ORDER BY p.accept_at DESC
        LIMIT 12
    ";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $bottleName = "Plastic";                 
                                    $userName   = $row['user_name'] ?? "Unknown User"; 
                                    $quantity   = (int)$row['totalBottles'];           
                                    $status     = $row['status'];
                                    $date       = $row['scanned_at'];
                                    $scan_id    = (int)$row['scan_id'];

                                    $timestamp = strtotime($date);
                                    $formatted_date = date("d M y", $timestamp);

                                    if ($status === "Confirm") {
                                        $color = "bg-green-500/20 text-green-400";
                                        $text = "Completed";
                                        $actionBtn = "<span class='text-green-400'>✓</span>";
                                    } elseif ($status === "Reject") {
                                        $color = "bg-red-500/20 text-red-400";
                                        $text = "Failed";
                                        $actionBtn = "<span class='text-red-400'>✗</span>";
                                    } else {
                                            $color = "bg-yellow-500/20 text-yellow-400";
                                        $text = "Pending";
                                        $actionBtn = "<button class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm' onclick='processBottle()'>Process</button>";
                                    }

                                    echo "<tr class='border-b border-gray-700/50 hover:bg-white/5 transition-colors'>";
                                    echo "<td class='py-3 px-4 text-gray-300'>" . htmlspecialchars($formatted_date) . "</td>";
                                    echo "<td class='py-3 px-4 text-white'>" . htmlspecialchars($userName) . "</td>";
                                    echo "<td class='py-3 px-4 text-white'>" . htmlspecialchars($bottleName) . "</td>";
                                    echo "<td class='py-3 px-4 text-gray-300'>" . $quantity . " Bottles</td>";
                                    echo "<td class='py-3 px-4'><span class='$color px-3 py-1 rounded-full text-sm'>" . htmlspecialchars($text) . "</span></td>";
                                    echo "<td class='py-3 px-4'>$actionBtn</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr class='border-b border-gray-700/50 hover:bg-white/5 transition-colors'>";
                                echo "<td class='py-3 px-4 text-gray-300' colspan='6'>No collections found</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</main>

<script>
   

   
</script>