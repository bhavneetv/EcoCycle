<?php
// session_start();
include "../config/conn.php";
include "../backend/autoMaintain.php";
if (!isset($_SESSION['User'])) {
    $user_login = 0;
    $role = "user";
    $user_idx = 0;
} else {
    $sql = "SELECT * FROM users WHERE email =  '$_SESSION[User]'";
    $user_idx = $_SESSION['User'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if ($result->num_rows == 0) {
        header("Location: ../config/logout.php");
    }
    $user_login = 1;
    $role = $row['role'];

    // echo $role;
}










// Send to all users
// $notify->sendNotification("EcoCycle Update", "Check out new recycling offers!");


?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
    <link rel="manifest" href="../assets/images/site.webmanifest">
    <!-- OneSignal SDK -->
    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <meta name="theme-color" content="black">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // ===== 1. Firebase Config =====
        const firebaseConfig = {
            apiKey: "AIzaSyBtQBM4OOBCrCUvWDq8fmdeuYf7irzsQi0",
            authDomain: "ecocycle-efdec.firebaseapp.com",
            projectId: "ecocycle-efdec",
            storageBucket: "ecocycle-efdec.firebasestorage.app",
            messagingSenderId: "33438843166",
            appId: "1:33438843166:web:7815a8ee66b2663ce0c90f",
            measurementId: "G-VBW821DQ5T"
        };

        // Init Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        // registerServiceWorker()

        // ===== Register Service Worker =====
        function registerServiceWorker() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('../firebase-messaging-sw.js')
                    .then(async (registration) => {
                        console.log('Service Worker registered:', registration);

                        try {
                            // Request notification permission
                            const permission = await Notification.requestPermission();
                            if (permission !== "granted") {
                                console.warn("Notification permission denied!");
                                return;
                            }

                            console.log("Notification permission granted.");

                            // Get FCM token
                            const currentToken = await messaging.getToken({
                                vapidKey: "BBIqxaj4MBIyTvMvvnYcaoZymqHgjrSMiuez5IA6ze3RezGMsvauYAYJ5ud-6u3qLR6jcFSsqB4X4XLXzeqIrHk",
                                serviceWorkerRegistration: registration
                            });

                            if (currentToken) {
                                let device = /Mobile|Android|iPhone|iPad/.test(navigator.userAgent) ? 'mobile' : 'desktop';
                                console.log("FCM Token:", currentToken);

                                // ✅ Save or update token in database
                                saveToken(currentToken, device); // role can be 'user' or 'recycler'
                            } else {
                                console.log("No registration token available.");
                            }

                        } catch (err) {
                            console.error("Error getting token:", err);
                        }

                        // Foreground messages
                        messaging.onMessage((payload) => {
                            console.log("Foreground message received:", payload);
                            new Notification(payload.notification.title, {
                                body: payload.notification.body,
                                icon: payload.notification.icon || '/firebase-logo.png'
                            });
                        });

                    })
                    .catch((err) => {
                        if (err.code === "messaging/invalid-argument" || err.code === "messaging/invalid-registration-token") {
                            deleteToken(currentToken, device);
                        }
                        console.error("Service Worker registration failed:", err);
                    });
            } else {
                console.warn("Service workers are not supported in this browser.");
            }
        }

        // ===== Save token via AJAX =====
        function saveToken(token, device) {
            fetch("../backend/saveToken.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        token: token,
                        device: device,
                    })
                })
                .then(res => res.json())
                .then(data => console.log("Server response:", data))
                .catch(err => console.error("Error saving token:", err));
        }

        // Call it on page load

        function deleteToken(token, device) {

            fetch("../backend/deleteToken.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        token: token,
                        device: device,
                    })
                })
                .then(res => res.json())
                .then(data => console.log("Server response:", data))
                .catch(err => console.error("Error saving token:", err));
        }
    </script>



    <title>EcoCycle Dashboard</title>

    <?php include "../includes/topInfo.php"; ?>


    <style>
        @import url('../assets/css/deshboard.css');
        @import url('../assets/css/commonUser.css');
    </style>
</head>

<body class="gradient-bg min-h-screen text-white relative">
    <!-- Navigation -->
    <?php include '../includes/navbar.php'; ?>

    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Notification Alert Modal -->
    <div id="notificationCard" class="fixed inset-0 bg-black hidden bg-opacity-50 flex items-center justify-center z-50 px-4" style="background-color: #00000057;backdrop-filter: blur(9px);">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
            <div class="p-6">
                <!-- Icon -->
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full">
                    <svg width="64px" class="w-8 h-8 text-blue-600" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M18.7491 9.70957V9.00497C18.7491 5.13623 15.7274 2 12 2C8.27256 2 5.25087 5.13623 5.25087 9.00497V9.70957C5.25087 10.5552 5.00972 11.3818 4.5578 12.0854L3.45036 13.8095C2.43882 15.3843 3.21105 17.5249 4.97036 18.0229C9.57274 19.3257 14.4273 19.3257 19.0296 18.0229C20.789 17.5249 21.5612 15.3843 20.5496 13.8095L19.4422 12.0854C18.9903 11.3818 18.7491 10.5552 18.7491 9.70957Z" stroke="#3e64ca" stroke-width="1.5"></path>
                            <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="#3e64ca" stroke-width="1.5" stroke-linecap="round"></path>
                        </g>
                    </svg>
                </div>

                <!-- Content -->
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Enable Notifications</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Stay updated with important alerts, messages, and updates. We'll only send you relevant notifications.</p>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="reject-notifications" onclick="denyNotifications()" class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Not Now
                    </button>
                    <button id="accept-notifications" onclick="acceptNotifications()" class="flex-1 px-4 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Allow Notifications
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="main-content">
        <?php if ($role == "usesr") { ?>
            <?php include "pages/dashboard.php"; ?>
        <?php } elseif ($role == "recycler") { ?>
            <?php include "pages/recyclerDashboard.php"; ?>
        <?php } elseif ($role == "admin") { ?>
            <?php include "pages/adminDashboard.php"; ?>
        <?php } ?>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- <div class="notification-card " id="notificationCard">
            <div class="card-header">
                <div class="bell-icon"></div>
                <div class="card-title">Stay Updated</div>
            </div>
            <div class="card-message">
                Get notified about important updates about recycling. We'll only send relevant notifications.
            </div>
            <div class="card-actions">
                <button class="btn btn-deny" onclick="denyNotifications()">Not Now</button>
                <button class="btn btn-accept" onclick="acceptNotifications()">Allow</button>
            </div>
        </div> -->

    <script>
        // After successful login
        // var userId = "<?php echo $user_idx; ?>"; // or whatever PHP session variable
        // if (window.AndroidApp) {
        //     window.AndroidApp.sendUserId(userId);
        // }

        function saveToken(token, device) {
            fetch('../backend/saveToken.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        token: token,
                        device: device
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 'success') {
                        console.log('Token saved successfully');
                    } else {
                        console.log('Failed to save token');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        //saveToken(333, 'mobile');
    </script>


    <script>
        // Check notification permission and show/hide card accordingly
        function checkNotificationPermission() {
            const userLogin = <?php echo $user_login; ?>;
            const card = document.getElementById('notificationCard');

            if ('Notification' in window) {
                const permission = Notification.permission;

                if (permission === 'default' && localStorage.getItem('notirejected') <= 9 && userLogin == 1) {
                    // Show the card with a slight delay for better UX
                    setTimeout(() => {
                        card.classList.remove('hidden');
                    }, 3000);
                } else {
                    // document.getElementById("resetNotification").style.display = 'none';
                    // Permission already granted or denied, hide the card
                    card.classList.add('hidden');
                }
            } else {
                // Notifications not supported, hide the card
                card.classList.add('hidden');
            }
        }

        // Handle accept button click
        function acceptNotifications() {
            document.getElementById("accept-notifications").classList.add("opacity-50");
            document.getElementById("accept-notifications").classList.add("cursor-not-allowed");
            document.getElementById("accept-notifications").disabled = true;

            const card = document.getElementById('notificationCard');

            Notification.requestPermission().then(permission => {

                if (permission === 'granted') {
                    localStorage.setItem('notirejected', 0);
                    registerServiceWorker();
                    // Show a test notification
                    new Notification('Notifications Enabled!', {
                        body: 'You\'ll now receive important updates.',
                        icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>'
                    });
                }
                hideCard();
            });
        }

        // Handle deny button click
        function denyNotifications() {
            if (!localStorage.getItem('notirejected')) {
                localStorage.setItem('notirejected', parseInt(0));
            } else {
                localStorage.setItem('notirejected', parseInt(localStorage.getItem('notirejected')) + 1);
            }
            hideCard();
        }

        // Hide the notification card
        function hideCard() {
            const card = document.getElementById('notificationCard');
            card.classList.add('hidden');

            // Remove from DOM after animation
            setTimeout(() => {
                card.style.display = 'none';
            }, 400);
        }



        // Initialize on page load
        document.addEventListener('DOMContentLoaded', checkNotificationPermission);

        // Listen for visibility changes to recheck permission
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                checkNotificationPermission();
            }
        });



        document.getElementById("resetNot").addEventListener("click", function() {
            // alert("Reset Notification");
            localStorage.setItem('notirejected', 0);
            window.location.reload();
        })

        function resetNotificationf() {
            if ('Notification' in window) {
                const permission = Notification.permission;

                if (permission === 'granted') {
                    //alert("Notification Permission Granted");
                    document.getElementById("resetNot").style.display = 'none';
                } else {
                    document.getElementById("resetNot").style.display = 'block';

                }


            }
        }
        resetNotificationf();
    </script>
    <script>
        <?php if (isset($_SESSION['User'])): ?>
            var userId = "<?php echo $_SESSION['User']; ?>";
        <?php else: ?>
            var userId = "0";
        <?php endif; ?>

        if (window.AndroidApp) {
            window.AndroidApp.sendUserId(userId);
        }
    </script>




    <script src="../assets/js/deshboardA.js"></script>
    <script src="app.js"></script>
    <!-- <script src="../public/js/firebase.js" type="module"></script> -->

</html>