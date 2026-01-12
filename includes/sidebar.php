<?php

if (isset($_SESSION['User'])) {

    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $role = $row['role'];
    if ($role === "user") {
        $dashboard = "dashboard";
        $leaderboard = "leaderboard";
        $history = "history";
        $hiddenUser = "hidden";
        $hiddenRecycler = "";
    } else if ($role === "recycler") {
        $dashboard = "recyclerDashboard";
        $leaderboard = "leaderboardrecycler";
        $history = "historyrecycler";
        $hiddenUser = "";
        $hiddenRecycler = "hidden";
    }
} else {
    $role = "user";
    $dashboard = "dashboard";
    $leaderboard = "leaderboard";
    $history = "history";
    $hiddenUser = "hidden";
    $hiddenRecycler = "";
}


?>



<aside id="sidebar"
    class="fixed left-0 top-16 h-full w-64 glass sidebar-transition transform -translate-x-full lg:translate-x-0 z-40">
    <div class="p-6">
        <nav class="space-y-3">
            <a href="?page=<?php echo $dashboard; ?>" data-page="<?php echo $dashboard; ?>" data-script="../assets/js/dashboard.js"
                class="nav-link data-link flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v1H8V5z"></path>
                </svg>
                <span>Dashboard</span>
            </a>


            <a href="?page=scan" data-page="scan" data-script="../assets/js/scan.js" s
                class="load-page scanPage  flex items-center active space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors <?php echo $hiddenRecycler; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z">
                    </path>
                </svg>
                <span>Scan Bottle</span>
            </a>

            <a href="?page=recyclerScan" data-page="recyclerScan" data-script="../assets/js/scan.js" s
                class="load-page scanPage flex items-center active space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors <?php echo $hiddenUser; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z">
                    </path>
                </svg>
                <span>Verify Request</span>
            </a>

            <a href="?page=reward" data-page="reward" data-script="../assets/js/reward.js"
                class="load-page flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors <?php echo $hiddenRecycler; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                    </path>
                </svg>
                <span>Rewards</span>
            </a>

            <a href="?page=recycleRequest" data-page="recycleRequest"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors <?php echo $hiddenUser; ?>">
                <svg fill="none" stroke="currentColor" class="w-5 h-5" viewBox="0 0 24 24" id="recycle" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path id="primary" d="M21.71,19a2.06,2.06,0,0,1-1.77,1h0L16,20a1,1,0,0,1,0-2h0l3.93.05c.07-.07,0-.12,0-.13l-2.37-4.1-.15.43a1,1,0,1,1-1.88-.67l.88-2.47a1,1,0,0,1,1.2-.63l2.73.73a1,1,0,1,1-.52,1.93l-.4-.1,2.25,3.89A2.09,2.09,0,0,1,21.71,19ZM15.58,8.2A1,1,0,0,0,16,6.83L13.83,3.05a2,2,0,0,0-1.77-1h0a2.09,2.09,0,0,0-1.81,1L8,6.94l-.11-.4a1,1,0,0,0-1.22-.71,1,1,0,0,0-.71,1.23l.73,2.73a1,1,0,0,0,1,.74.55.55,0,0,0,.18,0L10.4,10a1,1,0,1,0-.36-2l-.45.08L12,4.06s0-.06.13,0l2.13,3.79A1,1,0,0,0,15.58,8.2Zm-5.52,8.15a1,1,0,0,0-1.52,1.3l.3.35H4.1S4,18,4,17.89l2.21-3.73a1,1,0,0,0-.35-1.37,1,1,0,0,0-1.37.35L2.29,16.88a2,2,0,0,0,0,2.05A2.08,2.08,0,0,0,4.09,20h4.5l-.3.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l2-2a1,1,0,0,0,0-1.36Z" style="fill: #e4e4e4;"></path></g></svg>
                </svg>
                <span>Recycle Request</span>
            </a>  


            <a href="?page=history" data-page="history"
                class="load-page flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>History</span>
            </a>

            <a href="?page=leaderboard" data-page="leaderboard" 
                class="load-page flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M22,7H16.333V4a1,1,0,0,0-1-1H8.667a1,1,0,0,0-1,1v7H2a1,1,0,0,0-1,1v8a1,1,0,0,0,1,1H22a1,1,0,0,0,1-1V8A1,1,0,0,0,22,7ZM7.667,19H3V13H7.667Zm6.666,0H9.667V5h4.666ZM21,19H16.333V9H21Z"></path>
                    </g>
                </svg>
                <span>Leaderboard</span>
            </a>

            <a href="../contactus.php"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span>Support</span>
            </a>



            <!-- <a href="?page=contactus"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors <?php echo $hiddenUser; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span></span>
            </a> -->
        </nav>
    </div>
</aside>