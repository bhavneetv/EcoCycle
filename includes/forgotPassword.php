<?php
include "../config/conn.php";
if ((!isset($_GET['email']) || !isset($_GET['token']))) {
    header("Location: ../login.php");
    exit;
} else {
    $email = $_GET['email'];
    $token = $_GET['token'];
    $sql = "SELECT * FROM users WHERE email = '$email' AND token = '$token' AND status = 'active'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $expiry = $row['token_expiry'];
        if ($expiry < date("Y-m-d H:i:s")) {
            echo "Token Expired";
            header("Location: ../login.php");
            exit();
        } 
    } else {
        echo "User not found";
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
    <title>Eco Cycle | Reset Password</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="../assets/css/commonUser.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
    <link rel="manifest" href="../assets/images/site.webmanifest">

    <?php
    include "../includes/topInfo.php";
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

      
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.4), 0 0 60px rgba(59, 130, 246, 0.2);
        }

        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .slide-in {
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background-color: #ef4444;
        }

        .strength-medium {
            background-color: #f59e0b;
        }

        .strength-strong {
            background-color: #10b981;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .container-padding {
                padding: 1rem;
            }

            .card-padding {
                padding: 1.5rem;
            }

            .text-responsive {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <main class="min-h-screen flex items-center justify-center container-padding gradient-bg">
        <!-- Reset Password Container -->
        <div class="w-full max-w-md slide-in">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">EcoCycle</h1>
                <p class="text-gray-400 text-sm sm:text-base px-4">Reset your password to continue saving the planet!</p>
            </div>

            <!-- Reset Password Card -->
            <div class="glass rounded-2xl card-padding p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-xl mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Reset Password</h2>
                    <p class="text-gray-400 text-sm">Enter your new password below</p>
                </div>

                <!-- Email Display -->
                <div class="mb-6">
                    <div class="bg-white/5 border border-white/10 rounded-lg p-4 text-center">
                        <span class="text-sm text-gray-400">Resetting password for:</span>
                        <div id="userEmail" class="text-white font-medium mt-1 break-all"><?php echo $email; ?></div>
                    </div>
                </div>

                <!-- Reset Form -->
                <form id="resetPasswordForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="newPassword"
                                name="newPassword"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 pr-12"
                                placeholder="Enter new password"
                                required>
                            <button type="button" id="toggleNewPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex space-x-1">
                                <div id="strength1" class="flex-1 password-strength bg-gray-600"></div>
                                <div id="strength2" class="flex-1 password-strength bg-gray-600"></div>
                                <div id="strength3" class="flex-1 password-strength bg-gray-600"></div>
                            </div>
                            <div id="strengthText" class="text-xs text-gray-400 mt-1">Password strength</div>
                        </div>
                        <div id="newPasswordError" class="text-red-400 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="confirmPassword"
                                name="confirmPassword"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 pr-12"
                                placeholder="Confirm new password"
                                required>
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="confirmPasswordError" class="text-red-400 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-300 mb-2">Password Requirements:</h4>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li id="req-length" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                At least 8 characters long
                            </li>
                            <li id="req-uppercase" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                One uppercase letter
                            </li>
                            <li id="req-lowercase" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                One lowercase letter
                            </li>
                            <li id="req-number" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                One number
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <button
                            id="resetBtn"
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 btn-glow disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span class="btn-text">Reset Password</span>
                            <span class="spinner hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </span>
                        </button>

                        <a href="login.php" class="block">
                            <button
                                type="button"
                                class="w-full bg-white/10 hover:bg-white/20 text-gray-300 hover:text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 border border-white/20 hover:border-white/40">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Login
                            </button>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer Text -->
            <p class="text-center text-gray-400 text-sm mt-6 px-4">
                Your password will be encrypted and stored securely
            </p>
        </div>
    </main>

    <script>
        // Get URL parameters
        function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Set email from URL parameter or token
        document.addEventListener('DOMContentLoaded', function() {
            const email = getUrlParameter('email') || 'user@example.com';
            document.getElementById('userEmail').textContent = email;
        });

        // Toggle password visibility
        function togglePasswordVisibility(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);

            button.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                // Update icon
                const icon = button.querySelector('svg');
                if (type === 'text') {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    `;
                } else {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    `;
                }
            });
        }

        // Initialize password toggles
        togglePasswordVisibility('newPassword', 'toggleNewPassword');
        togglePasswordVisibility('confirmPassword', 'toggleConfirmPassword');

        // Password strength checker
        function checkPasswordStrength(password) {
            let score = 0;
            const requirements = {
                length: password.length >= 6,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };

            // Update requirement indicators
            updateRequirementIndicator('req-length', requirements.length);
            updateRequirementIndicator('req-uppercase', requirements.uppercase);
            updateRequirementIndicator('req-lowercase', requirements.lowercase);
            updateRequirementIndicator('req-number', requirements.number);

            // Calculate strength score
            Object.values(requirements).forEach(met => {
                if (met) score++;
            });

            return {
                score: score,
                requirements: requirements
            };
        }

        function updateRequirementIndicator(id, met) {
            const element = document.getElementById(id);
            const icon = element.querySelector('svg');

            if (met) {
                element.classList.remove('text-gray-400');
                element.classList.add('text-green-400');
                icon.innerHTML = `
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                `;
            } else {
                element.classList.remove('text-green-400');
                element.classList.add('text-gray-400');
                icon.innerHTML = `
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                `;
            }
        }

        // Update strength indicator
        function updateStrengthIndicator(score) {
            const indicators = ['strength1', 'strength2', 'strength3'];
            const strengthText = document.getElementById('strengthText');

            // Reset all indicators
            indicators.forEach(id => {
                const el = document.getElementById(id);
                el.className = 'flex-1 password-strength bg-gray-600';
            });

            if (score === 0) {
                strengthText.textContent = 'Password strength';
                strengthText.className = 'text-xs text-gray-400 mt-1';
            } else if (score <= 2) {
                document.getElementById('strength1').classList.add('strength-weak');
                strengthText.textContent = 'Weak password';
                strengthText.className = 'text-xs text-red-400 mt-1';
            } else if (score === 3) {
                document.getElementById('strength1').classList.add('strength-medium');
                document.getElementById('strength2').classList.add('strength-medium');
                strengthText.textContent = 'Medium password';
                strengthText.className = 'text-xs text-yellow-400 mt-1';
            } else {
                indicators.forEach(id => {
                    document.getElementById(id).classList.add('strength-strong');
                });
                strengthText.textContent = 'Strong password';
                strengthText.className = 'text-xs text-green-400 mt-1';
            }
        }

        // Password validation
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const newPasswordError = document.getElementById('newPasswordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        const resetBtn = document.getElementById('resetBtn');

        newPassword.addEventListener('input', () => {
            const password = newPassword.value;
            const strength = checkPasswordStrength(password);
            updateStrengthIndicator(strength.score);

            // Clear error
            hideError(newPasswordError, newPassword);

            // Validate confirm password if it has value
            if (confirmPassword.value) {
                validatePasswordMatch();
            }
        });

        confirmPassword.addEventListener('input', validatePasswordMatch);

        function validatePasswordMatch() {
            if (newPassword.value !== confirmPassword.value) {
                showError(confirmPasswordError, confirmPassword, 'Passwords do not match');
                return false;
            } else {
                hideError(confirmPasswordError, confirmPassword);
                return true;
            }
        }

        function showError(errorEl, inputEl, message) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-red-500');
            inputEl.parentElement.classList.add('error-shake');
            setTimeout(() => {
                inputEl.parentElement.classList.remove('error-shake');
            }, 500);
        }

        function hideError(errorEl, inputEl) {
            errorEl.classList.add('hidden');
            inputEl.classList.remove('border-red-500');
        }

        // Form submission
        document.getElementById('resetPasswordForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const password = newPassword.value;
            const confirmPass = confirmPassword.value;

            // Validate password strength
            const strength = checkPasswordStrength(password);
            if (strength.score < 4) {
                showError(newPasswordError, newPassword, 'Please meet all password requirements');
                return;
            }

            // Validate password match
            if (!validatePasswordMatch()) {
                return;
            }

            // Show loading
            const btnText = resetBtn.querySelector('.btn-text');
            const spinner = resetBtn.querySelector('.spinner');
            btnText.textContent = 'Resetting...';
            spinner.classList.remove('hidden');
            resetBtn.disabled = true;

            try {
                // Get token from URL
                const token = getUrlParameter('token') || 'demo-token';

                // Simulate API call - replace with your actual endpoint
                const response = await fetch('../backend/reset-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        token: token,
                        password: password
                    })
                });
                const data = await response.json();
                console.log(data);

                // Simulate delay
                await new Promise(resolve => setTimeout(resolve, 2000));

                if (data) { // Replace with actual response check
                    // Success
                    alert('Password reset successfully! You can now log in with your new password.');
                    window.location.href = '../login.php';
                } else {
                    throw new Error('Failed to reset password');
                }

            } catch (error) {
                showError(newPasswordError, newPassword, 'Failed to reset password. Please try again.');
            } finally {
                // Reset button
                btnText.textContent = 'Reset Password';
                spinner.classList.add('hidden');
                resetBtn.disabled = false;
            }
        });
    </script>
</body>

</html>