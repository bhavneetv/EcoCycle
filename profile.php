<?php

include "config/conn.php";
if(isset($_SESSION['User'])){
    $sql = "SELECT * FROM users WHERE email = '$_SESSION[User]'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $fullName = $row['full_name'];
        
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
        $pincode = $row['pincode'];
        $country = $row['country'];
        $wallet = round(($row["total_points"] - $row["redeemed_points"])*0.25);
        $points = $row["total_points"];
        $co2 = round($row["carbonFree"]/1000 , 2);

        if($row['role'] == "admin"){
            $roleLink = "admin.php";
        }
        else if($row['role'] == "recycler"){
            $roleLink = "recycler.php";
        }
        else{
            $roleLink = "dashboard/index.php?page=history";
        }

        $sql = "SELECT count(*) FROM scans WHERE user_id = '$row[user_id]'"; 
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $scans = $row["count(*)"];



    }

}
else{
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - EcoCycle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
    <link rel="manifest" href="assets/images/site.webmanifest">


</head>

<body class="gradient-bg min-h-screen text-white">
    <!-- Header -->
    <header class="p-6 pb-0">
        <div class="max-w-4xl mx-auto">
            <button onclick="goToHistory()" class="back-btn flex items-center space-x-2 text-gray-300 hover:text-white mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="font-medium" onclick="history.back()">Back to Home</span>
            </button>
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">My Profile</h1>
                <p class="text-gray-300">Manage your EcoCycle account details</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-6 pb-8">
        <div class="max-w-2xl mx-auto">
            <!-- Profile Card -->
            <div class="glass rounded-2xl p-8 card-hover">
                <!-- Avatar Section -->
                <div class="text-center mb-8">
                    <div class="profile-avatar w-24 h-24 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl font-bold text-white shadow-lg">
                        <?php echo strtoupper(substr($fullName, 0, 1)); ?>
                    </div>
                    <h2 class="text-xl font-semibold text-white mb-1"><?php echo $fullName; ?></h2>
                    <p class="text-gray-400">EcoCycle Member since <?php echo date("Y"); ?></p>
                </div>

                <!-- Profile Form -->
                <form id="profileForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="fullName" class="block text-sm font-medium text-gray-300 mb-2">
                                Full Name
                            </label>
                            <input 
                                value="<?php echo $fullName; ?>"
                                type="text" 
                                id="fullName" 
                                disabled
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input 
                                value="<?php echo $email; ?>"
                                type="email" 
                                id="email" 
                                disabled
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input 
                                value="<?php echo $phone; ?>"
                                type="number" 
                                id="phone" 
                                disabled

                                class="input-focus w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                        </div>

                        <!-- Pincode -->
                        <div>
                            <label for="pincode" class="block text-sm font-medium text-gray-300 mb-2">
                                Pincode <span class="text-red-500">*</span> 
                            </label>
                            <input 
                                value="<?php echo $pincode; ?>"
                                type="number" 
                                id="pincode" 
                                disabled
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        <div>
                            <label for="pincode" class="block text-sm font-medium text-gray-300 mb-2">
                                Country <span class="text-red-500">*</span> <span class="text-gray-400">for leaderboard</span> 
                            </label>
                            <input 
                                value="<?php echo $country; ?>"
                                type="text" 
                                id="countryx" 
                                placeholder="Enter your Country"
                                disabled
                                class="input-focus w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                        </div>
                    </div>
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-300 mb-2">
                            Address <span class="text-red-500">*</span> 
                        </label>
                        <textarea 
                            value="<?php echo $address; ?>"
                            id="address" 
                            rows="3"
                            disabled
                            required
                            class="input-focus w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 disabled:opacity-60 disabled:cursor-not-allowed resize-none"
                        ><?php echo $address; ?></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button 
                            type="button" 
                            id="editBtn"
                            class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105"
                        >
                            Edit Details
                        </button>
                        
                        <button 
                            type="button" 
                            onclick="goToHistory('<?php echo $roleLink; ?>')"
                            class="flex-1 bg-white/10 hover:bg-white/20 border border-gray-600 hover:border-gray-500 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300"
                        >
                            View My History
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Summary Card -->
            <div class="glass rounded-2xl p-6 mt-6 card-hover">
                <h3 class="text-lg font-semibold text-white mb-4">Account Summary</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-400"><?php echo $scans; ?></div>
                        <div class="text-sm text-gray-400">Bottles Recycled</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-400"><?php echo $points; ?></div>
                        <div class="text-sm text-gray-400">Points Earned</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">₹ <?php echo $wallet; ?></div>
                        <div class="text-sm text-gray-400">Wallet Balance</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-400"><?php echo $co2; ?> kg</div>
                        <div class="text-sm text-gray-400">CO₂ Saved</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Toast -->
    <div id="successToast" class="toast fixed top-6 right-6 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">Profile updated successfully!</span>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-black py-6 mt-12">
        <div class="px-6">
            <p class="text-center text-gray-400 text-sm">© 2025 EcoCycle. All rights reserved.</p>
        </div>
    </footer>

    
</body>
<script src="assets/js/profile.js"></script>

</html>