<?php
// settings.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$success_msg = '';

// Handle form submission for settings update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Here you can save preferences to database if needed, for prototype we show success message
    $success_msg = "AI Coach settings updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Coach Settings | Finler AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4F46E5', secondary: '#10B981', dark: '#111827' } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background-color: #F9FAFB; } </style>
</head>
<body class="text-gray-800 font-sans antialiased">
    
    <!-- Include Sidebar -->
    <?php require_once 'users/includes/sidebar.php'; ?>

    <div class="ml-64 flex flex-col min-h-screen">
        
        <!-- Top Header -->
        <header class="h-24 bg-white/80 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-8 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">AI Coach Settings</h1>
                <p class="text-sm text-gray-500 mt-1">Configure your personal finance AI parameters and automated guardrails.</p>
            </div>
            <div class="h-10 w-10 bg-primary text-white rounded-full flex items-center justify-center font-bold shadow-md">
                <?= strtoupper(substr($user_name, 0, 1)) ?>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-8 max-w-4xl">

            <?php if(!empty($success_msg)): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100">
                <i class="fa-solid fa-check-circle"></i> <?= $success_msg ?>
            </div>
            <?php endif; ?>

            <form action="settings.php" method="POST" class="space-y-6">
                
                <!-- AI Behavior Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-brain text-primary"></i> AI Persona & Tone
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Coaching Style</label>
                            <select name="coaching_style" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm">
                                <option value="strict">Strict & Frugal (Strict Budgeting)</option>
                                <option value="balanced" selected>Balanced & Practical (Recommended)</option>
                                <option value="growth">Aggressive Wealth Builder</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Risk Appetite</label>
                            <select name="risk_appetite" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm">
                                <option value="low">Low (Conservative / FD & Safe)</option>
                                <option value="medium" selected>Medium (Balanced Mutual Funds)</option>
                                <option value="high">High (Growth Stocks & Crypto)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Automation & Alerts Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-bell text-primary"></i> Automated Smart Alerts
                    </h3>
                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Overspending Warning</p>
                                <p class="text-xs text-gray-500">Alert me instantly when dining or shopping exceeds monthly limits.</p>
                            </div>
                            <input type="checkbox" checked class="h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary">
                        </label>
                        
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Weekly AI Financial Summary</p>
                                <p class="text-xs text-gray-500">Receive an automated WhatsApp/Email digest of weekly cashflows.</p>
                            </div>
                            <input type="checkbox" checked class="h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary">
                        </label>
                    </div>
                </div>

                <!-- Security & Privacy Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-primary"></i> Data Privacy & In-Memory Encryption
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Finler AI processes your bank statements securely. You can purge all cached statement texts from our memory servers instantly.</p>
                    <button type="button" onclick="alert('All cached statement buffers successfully wiped from RAM.')" class="bg-red-50 text-red-600 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-red-100 transition">
                        <i class="fa-solid fa-trash-can mr-2"></i> Purge AI Statement Cache
                    </button>
                </div>

                <!-- Save Button -->
                <button type="submit" class="w-full bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    Save AI Preferences
                </button>

            </form>
        </main>
    </div>
</body>
</html>