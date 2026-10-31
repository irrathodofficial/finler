<?php
// users/includes/sidebar.php
// Get current page name to highlight the active menu item
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="w-64 bg-white shadow-[4px_0_24px_rgba(0,0,0,0.02)] h-screen fixed top-0 left-0 flex flex-col z-50">
    <!-- Logo Section -->
    <div class="h-24 flex items-center px-8 border-b border-gray-100">
        <a href="dashboard.php" class="flex items-center">
            <!-- Make sure the logo path is correct relative to dashboard.php -->
            <img src="assets/images/logo.png" alt="Finler AI" class="h-12 w-auto" onerror="this.outerHTML='<span class=\'font-bold text-2xl text-gray-900 tracking-tight\'>Finler <span class=\'text-indigo-600\'>AI</span></span>'">
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
        
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition <?= ($current_page == 'dashboard.php') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' ?>">
            <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-gray-600 hover:bg-gray-50 hover:text-indigo-600">
            <i class="fa-brands fa-google w-5"></i> Gmail Sync
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-gray-600 hover:bg-gray-50 hover:text-indigo-600">
            <i class="fa-solid fa-list-ul w-5"></i> Transactions
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-gray-600 hover:bg-gray-50 hover:text-indigo-600">
            <i class="fa-solid fa-sliders w-5"></i> Goal Simulator
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition text-gray-600 hover:bg-gray-50 hover:text-indigo-600">
            <i class="fa-solid fa-brain w-5"></i> AI Coach Settings
        </a>
    </nav>

    <!-- Bottom Section (Logout) -->
    <div class="p-4 border-t border-gray-100 mb-4">
        <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition">
            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Log Out
        </a>
    </div>
</aside>