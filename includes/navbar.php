<?php

// require("../config/conn.php");

if (isset($_SESSION['User'])) {
    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $loginName = "Logout";
    $loginLink = "../config/logout.php";
    $userNameFirst = strtoupper($row['full_name'][0]);
    $pincode = $row['pincode'];
    if($pincode == "0"){
        $pinAlert = "(Or Please update your pincode)";
    }
    else{
        $pinAlert = "";
    }


} else {

    $pinAlert = " Login to get notifications";
    $loginName = "Login";
    $loginLink = "../login.php";


    $userNameFirst = "G";
}


?>

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 glass-nav navbar-scroll">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left: Logo & Brand -->
        <div class="flex items-center space-x-3">
            <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-white/10 transition-colors lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex items-center space-x-2">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white hidden sm:block">EcoCycle</span>
            </div>
        </div>

        <!-- Center: Page Title -->
        <h1 class="text-lg font-semibold text-white capitalize" id="pageTitle">Login</h1>

        <!-- Right: User Actions -->
        <div class="flex items-center space-x-3">
            <!-- Notifications -->
            <div class="relative group">
                <button id="notificationToggle" class="relative p-2 rounded-lg hover:bg-white/10 transition-all duration-200 hover:scale-105">
                    <svg width="24" class="w-6 h-6" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M12.0009 5C13.4331 5 14.8066 5.50571 15.8193 6.40589C16.832 7.30606 17.4009 8.52696 17.4009 9.8C17.4009 11.7691 17.846 13.2436 18.4232 14.3279C19.1606 15.7133 19.5293 16.406 19.5088 16.5642C19.4849 16.7489 19.4544 16.7997 19.3026 16.9075C19.1725 17 18.5254 17 17.2311 17H6.77066C5.47638 17 4.82925 17 4.69916 16.9075C4.54741 16.7997 4.51692 16.7489 4.493 16.5642C4.47249 16.406 4.8412 15.7133 5.57863 14.3279C6.1558 13.2436 6.60089 11.7691 6.60089 9.8C6.60089 8.52696 7.16982 7.30606 8.18251 6.40589C9.19521 5.50571 10.5687 5 12.0009 5ZM12.0009 5V3M9.35489 20C10.0611 20.6233 10.9888 21.0016 12.0049 21.0016C13.0209 21.0016 13.9486 20.6233 14.6549 20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-xs font-bold text-white animate-pulse" id="notificationCountt">3</span>
                </button>

                <!-- Enhanced Notification Dropdown -->
                <div id="notificationDropdown" class="absolute right-0 mt-3 w-80 sm:w-96 bg-black/90 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-100 hidden transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-600/50 bg-gradient-to-r from-gray-800/50 to-gray-700/50 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-lg font-bold text-white">Notifications</h3>
                                <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-white" id="notificationCount"></span>
                                </div>
                            </div>
                            <button class="text-xs text-blue-400 hover:text-blue-300 transition-colors px-3 py-1 rounded-md hover:bg-white/10" id="markAllRead">
                                Mark all read
                            </button>
                        </div>
                    </div>

                    <!-- Notification Items -->
                    <div class="max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-transparent" id="notificationsContainer">
                  
                       
                      
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-600/50 bg-gradient-to-r from-gray-800/50 to-gray-700/50 rounded-b-xl">
                        <div class="flex items-center justify-between">
                           
                            <button id="resetNot" class="text-sm text-red-400 hover:text-red-300 transition-colors px-3 py-1 rounded-md hover:bg-red-500/10">
                                Reset Notification Permission
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="relative group">
                <button id="profileToggle"
                    class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white/10 transition-colors">
                    <div id="profileAvatar"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm"
                        style="background-color: #10b981;">
                        <?php echo $userNameFirst; ?>
                    </div>
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-black/90 backdrop-blur-xl border border-white/10 rounded-lg shadow-lg opacity-100 hidden transition-all duration-200">
                    <div class="py-2">
                        <a href="../profile.php"
                            class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 transition-colors">Profile</a>
                        <hr class="my-2 border-gray-600">
                        <a href="<?php echo $loginLink; ?>"
                            class="block px-4 py-2 text-sm text-red-400 hover:bg-white/10 transition-colors"><?php echo $loginName; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
   
        async function loadNotifications() {
            try {
                const response = await fetch('../notificationManager/fetchNotification.php');
                const data = await response.json();
                // console.log(data);

                const container = document.getElementById('notificationsContainer');
                container.innerHTML = '';

                if (!data.status || data.count === 0) {
                    document.getElementById('notificationCount').style.display = 'none';
                    document.getElementById('notificationCountt').style.display = 'none';
                    container.innerHTML = `
                <div class="px-4 py-3 text-gray-400 text-sm text-center">
                    No new notifications <?php echo $pinAlert; ?>
                </div>`;
                    return;
                }
              
                // document.getElementById('notificationCount').style.display = 'block';
                document.getElementById('notificationCountt').textContent = data.count;
                // document.getElementById('notificationCount').textContent = data.count;

                data.notifications.forEach(notification => {
                    const timeAgo = formatTimeAgo(notification.created_at);

                    container.innerHTML += `
                <div class="px-6 py-4 border-b border-gray-700/30 hover:bg-white/5 transition-all duration-200 hover:translate-x-1">
                            <div class="flex items-start space-x-4">
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-black"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm text-white font-semibold truncate">${notification.title}</p>
                                        <span class="text-xs text-gray-400 ml-2 flex-shrink-0">${timeAgo}</span>
                                    </div>
                                    <p class="text-sm text-gray-300 mb-2 line-clamp-2">${notification.message}</p>
                                    <div class="flex items-center space-x-2 flex-wrap">
                                        <span class="inline-flex px-2 py-1 text-xs bg-green-500/20 text-green-400 rounded-full">Completed</span>
                                        <span class="text-xs text-gray-500">#WP2024-001</span>
                                    </div>
                                </div>
                            </div>
                        </div>
            `;
                });
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        }

    function formatTimeAgo(dateTime) {
        const now = new Date();
        const created = new Date(dateTime);
        const diff = Math.floor((now - created) / 1000);

        if (diff < 60) return "Just now";
        if (diff < 3600) return Math.floor(diff / 60) + " mins ago";
        if (diff < 86400) return Math.floor(diff / 3600) + " hours ago";
        return Math.floor(diff / 86400) + " days ago";
    }

    loadNotifications();
    setInterval(loadNotifications, 8000);



    document.getElementById('markAllRead').addEventListener('click', function() {
        updateRead();

        const dropdown = document.getElementById('notificationDropdown');
        // dropdown.class   List.toggle('hidden');
    });


    function updateRead(){
        fetch('../notificationManager/updateRead.php')
        .then(data => {
            console.log(data);
            if (data == 'success') {
                
                console.log('Read updated successfully');
            } else {
                console.log('Failed to update read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        
    }
    document.getElementById("profileToggle").addEventListener("click", function() {
        // alert("Profile clicked");
        const dropdown = document.getElementById('profileDropdown');

        dropdown.classList.toggle('hidden');
    })
    document.getElementById("notificationToggle").addEventListener("click", function() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    })


    
</script>

