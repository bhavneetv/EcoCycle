<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - EcoCycle</title>
    <link rel="stylesheet" href="assets/css/commonUser.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
    <link rel="manifest" href="assets/images/site.webmanifest">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

       
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .form-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.5);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .success-message {
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.5s ease;
        }

        .success-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .contact-item {
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .gradient-bg {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                background-attachment: scroll;
            }
        }
    </style>
</head>

<body class="gradient-bg min-h-screen text-white">
    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 glass-nav z-40 navbar-scroll">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-white">EcoCycle</h1>
                    </div>
                </div>

                <!-- Navigation Links -->
              
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 floating-animation">Contact Us</h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Have questions about EcoCycle? We'd love to hear from you. Get in touch and let's make the world greener together!
                </p>
            </div>

            <!-- Contact Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Contact Form -->
                <div class="glass rounded-2xl p-8 card-hover">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-white mb-2">Send us a Message</h2>
                        <p class="text-gray-300">Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>

                    <form id="contactForm" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" required
                                class="form-input w-full px-4 py-3 rounded-lg text-white placeholder-gray-400 focus:outline-none"
                                placeholder="Enter your full name">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="form-input w-full px-4 py-3 rounded-lg text-white placeholder-gray-400 focus:outline-none"
                                placeholder="Enter your email address">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                class="form-input w-full px-4 py-3 rounded-lg text-white placeholder-gray-400 focus:outline-none"
                                placeholder="Enter your phone number">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required
                                class="form-input w-full px-4 py-3 rounded-lg text-white placeholder-gray-400 focus:outline-none resize-none"
                                placeholder="Tell us how we can help you..."></textarea>
                        </div>

                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-500/30">
                            <span id="submitText">Send Message</span>
                            <svg id="submitLoader" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Success Message -->
                    <div id="successMessage" class="success-message mt-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-green-400 font-medium">Thank you! Your message has been sent successfully. We'll get back to you soon.</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-6">
                    <!-- Company Info -->
                    <div class="glass rounded-2xl p-8 card-hover">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-white mb-2">Get in Touch</h2>
                            <p class="text-gray-300">We're here to help you with all your recycling needs and questions.</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Office Address -->
                            <div class="contact-item flex items-start space-x-4">
                                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">Office Address</h3>
                                    <p class="text-gray-300">
                                        Ambala, Haryana 134001, India
                                    </p>
                                </div>
                            </div>

                            <!-- Support Email -->
                            <div class="contact-item flex items-start space-x-4">
                                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">Support Email</h3>
                                    <p class="text-gray-300">ecocycleofficial@gmail.com</p>
                                    <p class="text-sm text-gray-400">We typically respond within 24 hours</p>
                                </div>
                            </div>

                            <!-- Helpline Number -->
                            <div class="contact-item flex items-start space-x-4">
                                <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">Helpline Number</h3>
                                    <p class="text-gray-300">+91 93505XXXX</p>
                                    <p class="text-sm text-gray-400">Available 24/7 for support</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="glass rounded-2xl p-8 card-hover">
                        <h3 class="text-xl font-bold text-white mb-4">Why Choose EcoCycle?</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-300">Earn rewards for every bottle recycled</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-300">Track your environmental impact</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-300">Join a community of eco-warriors</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-300">24/7 customer support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-black py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">EcoCycle</span>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-400 text-sm">© 2025 EcoCycle. All rights reserved.</p>
                    <p class="text-gray-500 text-xs mt-1">Making the world greener, one bottle at a time.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Contact Form Handling
        const contactForm = document.getElementById('contactForm');
      
        // Navbar Scroll Effect
        const navbar = document.getElementById('navbar');
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            
            if (scrollTop > 50) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
            
            lastScrollTop = scrollTop;
        });

        // Form Input Animations
        const formInputs = document.querySelectorAll('.form-input');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Initialize Page
        document.addEventListener('DOMContentLoaded', () => {
            // Add loading animation
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease';
                document.body.style.opacity = '1';
            }, 100);
            
            // Add entrance animations
            const animatedElements = document.querySelectorAll('.card-hover, .contact-item');
            animatedElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Mobile Touch Optimizations
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
            
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function(event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
        }
    </script>
</body>

</html>