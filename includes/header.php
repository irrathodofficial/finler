<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finler AI | Your Personal Finance Coach</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5', 
                        secondary: '#10B981', 
                        dark: '#111827', 
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #4F46E5, #10B981);
        }
        .hero-bg {
            background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.05), transparent 50%),
                        radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.05), transparent 50%);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased selection:bg-primary selection:text-white">

    <nav class="bg-white shadow-sm fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center"> <!-- Height increased for bigger logo -->
                
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <!-- Logo size increased here (h-16) -->
                        <img src="assets/images/logo.png" alt="Finler AI Logo" class="h-12 md:h-18 w-auto transition-all" onerror="this.outerHTML='<span class=\'font-bold text-3xl text-dark tracking-tight\'>Finler <span class=\'text-primary\'>AI</span></span>'">
                    </a>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="index.php#features" class="text-gray-600 hover:text-primary transition font-medium">Features</a>
                    <a href="index.php#how-it-works" class="text-gray-600 hover:text-primary transition font-medium">How it Works</a>
                    <a href="login.php" class="text-gray-600 hover:text-primary transition font-semibold">Log in</a>
                    <a href="signup.php" class="bg-primary hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md hover:shadow-lg">Get Started Free</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="h-24"></div>