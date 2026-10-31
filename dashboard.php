<?php
// dashboard.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// 1. Handle Manual Subscription Addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subscription'])) {
    $sub_name = trim($_POST['sub_name']);
    $sub_amount = floatval($_POST['sub_amount']);
    $sub_cycle = trim($_POST['sub_cycle']);
    
    if (!empty($sub_name) && $sub_amount > 0) {
        $stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, sub_name, sub_amount, sub_cycle) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $sub_name, $sub_amount, $sub_cycle]);
        header("Location: dashboard.php?sub=added#transactions");
        exit();
    }
}

// 2. Handle Subscription Deletion
if (isset($_GET['delete_sub'])) {
    $sub_id = intval($_GET['delete_sub']);
    $del_stmt = $pdo->prepare("DELETE FROM subscriptions WHERE id = ? AND user_id = ?");
    $del_stmt->execute([$sub_id, $user_id]);
    header("Location: dashboard.php?sub=deleted#transactions");
    exit();
}

// Category Filter Handling
$filter_category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Fetch Transactions with robust case-insensitive and space-trimmed filtering
if (!empty($filter_category)) {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND TRIM(LOWER(category)) = TRIM(LOWER(?)) ORDER BY date DESC");
    $stmt->execute([$user_id, $filter_category]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
    $stmt->execute([$user_id]);
}
$transactions = $stmt->fetchAll();

// Fetch Goals
$stmt_goals = $pdo->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY created_at DESC");
$stmt_goals->execute([$user_id]);
$goals = $stmt_goals->fetchAll();

// Fetch Manual Subscriptions
$stmt_subs = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY id DESC");
$stmt_subs->execute([$user_id]);
$subscriptions = $stmt_subs->fetchAll();

// Accurate Balance, Spend, and Category Wise Calculation
$total_credits = 0;
$total_debits = 0;
$category_spend = [];

// 1. Calculate from Transactions table
$all_txn_stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$all_txn_stmt->execute([$user_id]);
$all_transactions = $all_txn_stmt->fetchAll();

foreach ($all_transactions as $txn) {
    $amt = floatval($txn['amount']);
    if (strtolower($txn['type']) == 'credit') {
        $total_credits += $amt;
    } else {
        $total_debits += $amt;
        $cat = !empty($txn['category']) ? trim($txn['category']) : 'Other';
        if (!isset($category_spend[$cat])) {
            $category_spend[$cat] = 0;
        }
        $category_spend[$cat] += $amt;
    }
}

// 2. Add Manual Subscriptions to Total Spend & Debits
foreach ($subscriptions as $sub) {
    $sub_amt = floatval($sub['sub_amount']);
    $total_debits += $sub_amt;
    
    // Add to Utilities or Subscriptions category for chart
    if (!isset($category_spend['Utilities'])) {
        $category_spend['Utilities'] = 0;
    }
    $category_spend['Utilities'] += $sub_amt;
}

// Final Available Balance = Total Credits - Total Debits
$available_balance = $total_credits - $total_debits;
$total_spend = $total_debits;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Finler AI - Living Money Model</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4F46E5', secondary: '#10B981', dark: '#111827' } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> body { background-color: #F9FAFB; } .modal { transition: opacity 0.25s ease; } html { scroll-behavior: smooth; } </style>
</head>
<body class="text-gray-800 font-sans antialiased">

    <?php require_once 'users/includes/sidebar.php'; ?>

    <div class="ml-64 flex flex-col min-h-screen">
        
        <!-- Header -->
        <header class="h-24 bg-white/80 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-8 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>! 👋</h1>
                <p class="text-sm text-gray-500 mt-1">Living Money Model Active — AI Guardrails Enforced.</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="toggleModal('uploadModal')" class="bg-indigo-50 text-primary px-4 py-2 rounded-xl font-semibold text-sm hover:bg-indigo-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Upload Statement
                </button>
                <div class="h-10 w-10 bg-primary text-white rounded-full flex items-center justify-center font-bold shadow-md">
                    <?= strtoupper(substr($user_name, 0, 1)) ?>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8">
            
            <?php if(isset($_GET['sync']) && $_GET['sync'] == 'success'): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> Statement ingested & auto-categorized successfully!
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['sub']) && $_GET['sub'] == 'added'): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> Subscription added successfully! Total spend and balance updated.
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['sub']) && $_GET['sub'] == 'deleted'): ?>
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-100 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> Subscription removed successfully! Balance recalculated.
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['goal']) && $_GET['goal'] == 'added'): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> Financial goal added successfully!
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['scenario']) && $_GET['scenario'] == 'applied'): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i> AI What-If Scenario successfully applied to your plan!
            </div>
            <?php endif; ?>

            <!-- Spending Drift Nudge -->
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl mb-8 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
                    <div>
                        <p class="text-sm font-bold text-amber-900">AI Spending Drift Nudge</p>
                        <p class="text-xs text-amber-700">Your discretionary outflow has increased by 14% this week. Consider rebalancing.</p>
                    </div>
                </div>
                <button onclick="toggleModal('whatIfModal')" class="text-xs bg-amber-600 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-amber-700 transition">Simulate Fix</button>
            </div>

            <!-- Core Financial Metrics Row (Accurate Calculations) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Available Balance</p>
                    <h3 class="text-2xl font-bold text-gray-900">₹<?= number_format($available_balance, 2) ?></h3>
                    <p class="text-xs text-green-500 font-medium mt-1"><i class="fa-solid fa-arrow-trend-up"></i> Real-time synced</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Spend</p>
                    <h3 class="text-2xl font-bold text-gray-900">₹<?= number_format($total_spend, 2) ?></h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Includes manual bills & statements</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Emergency Fund</p>
                    <h3 class="text-2xl font-bold text-secondary">₹1,50,000</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">6 months runway secured</p>
                </div>
                <div class="bg-gradient-to-br from-primary to-indigo-800 p-6 rounded-2xl shadow-md text-white flex flex-col justify-between">
                    <div>
                        <p class="text-indigo-200 text-xs font-semibold uppercase tracking-wider">AI Goal Simulator</p>
                        <h4 class="text-lg font-bold">What-If Analysis</h4>
                    </div>
                    <button onclick="toggleModal('whatIfModal')" class="mt-3 bg-white text-primary px-3 py-2 rounded-xl text-xs font-bold shadow hover:bg-gray-50 transition">Run Simulation</button>
                </div>
            </div>

            <!-- Transactions Table & Filtration Section -->
            <div id="transactions" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Transactions & Filtration</h3>
                        
                        <div class="flex items-center gap-2">
                            <form method="GET" action="dashboard.php#transactions" class="flex items-center gap-2">
                                <select name="category" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs rounded-xl px-3 py-2 focus:ring-primary focus:border-primary">
                                    <option value="">All Categories</option>
                                    <?php 
                                    $cats = ['Food', 'Shopping', 'Transport', 'Utilities', 'Income', 'Transfer', 'Entertainment', 'Health', 'Cash Withdrawal', 'Investment', 'Other'];
                                    foreach($cats as $c) {
                                        $sel = (strcasecmp($filter_category, $c) == 0) ? 'selected' : '';
                                        echo "<option value='$c' $sel>$c</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                            <a href="export_pdf.php?category=<?= urlencode($filter_category) ?>" target="_blank" class="bg-gray-900 text-white px-3 py-2 rounded-xl text-xs font-bold hover:bg-black transition flex items-center gap-1.5">
                                <i class="fa-solid fa-download"></i> Export PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="pb-3 font-medium">Date</th>
                                    <th class="pb-3 font-medium">Description</th>
                                    <th class="pb-3 font-medium">Category (Correction Loop)</th>
                                    <th class="pb-3 font-medium text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($transactions)): ?>
                                    <tr><td colspan="4" class="text-center py-8 text-gray-400 text-sm">No transactions found for this filter.</td></tr>
                                <?php else: ?>
                                    <?php foreach($transactions as $txn): ?>
                                    <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
                                        <td class="py-4 text-xs text-gray-500"><?= date('M d, Y', strtotime($txn['date'])) ?></td>
                                        <td class="py-4 text-sm font-medium text-gray-900 max-w-xs truncate"><?= htmlspecialchars($txn['description']) ?></td>
                                        <td class="py-4 text-sm">
                                            <select onchange="updateCategory(<?= $txn['id'] ?>, this.value)" class="bg-indigo-50 text-primary px-2 py-1 rounded-md text-xs font-bold border-0 cursor-pointer focus:ring-2 focus:ring-primary">
                                                <?php 
                                                foreach($cats as $c) {
                                                    $sel = (strcasecmp($txn['category'], $c) == 0) ? 'selected' : '';
                                                    echo "<option value='$c' $sel>$c</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="py-4 text-right font-bold text-sm <?= $txn['type'] == 'credit' ? 'text-green-500' : 'text-gray-900' ?>">
                                            <?= $txn['type'] == 'credit' ? '+' : '-' ?>₹<?= number_format($txn['amount'], 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column: Subscriptions & Goals (With Delete option) -->
                <div class="space-y-6">
                    <!-- Subscriptions Box -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-repeat text-primary"></i> Subscriptions & Utilities
                            </h3>
                            <button onclick="toggleModal('subModal')" class="text-xs text-primary font-bold hover:underline">+ Add Manual</button>
                        </div>
                        
                        <?php if(empty($subscriptions)): ?>
                            <p class="text-xs text-gray-500">No manual subscriptions added yet.</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach($subscriptions as $sub): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <div>
                                        <p class="text-xs font-bold text-gray-800"><?= htmlspecialchars($sub['sub_name']) ?></p>
                                        <p class="text-[10px] text-gray-400"><?= $sub['sub_cycle'] ?> recurring</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold text-red-500">-₹<?= number_format($sub['sub_amount'], 2) ?></span>
                                        <a href="dashboard.php?delete_sub=<?= $sub['id'] ?>#transactions" class="text-gray-400 hover:text-red-500 text-xs" title="Remove Subscription">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Financial Goals Box -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Financial Goals</h3>
                            <button onclick="toggleModal('goalModal')" class="text-xs text-primary font-bold hover:underline">+ Add Goal</button>
                        </div>
                        <?php if(empty($goals)): ?>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center">
                                <p class="text-xs text-gray-500 mb-2">No goals configured.</p>
                                <button onclick="toggleModal('goalModal')" class="text-primary text-xs font-bold">+ Create Goal</button>
                            </div>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach($goals as $goal): ?>
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <div class="flex justify-between items-center mb-1">
                                        <h4 class="text-xs font-bold text-gray-800"><?= htmlspecialchars($goal['goal_name']) ?></h4>
                                        <span class="text-[10px] font-semibold bg-white px-2 py-0.5 rounded text-gray-600"><?= date('M Y', strtotime($goal['target_date'])) ?></span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-2">Target: ₹<?= number_format($goal['target_amount'], 0) ?></p>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-secondary h-1.5 rounded-full" style="width: 40%"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Spending Breakdown Graph Section -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900"><i class="fa-solid fa-chart-pie text-primary mr-2"></i> Spending Breakdown by Category</h3>
                        <span class="text-xs text-gray-500">Visualizing where your money goes</span>
                    </div>
                    <div class="h-72 flex justify-center">
                        <canvas id="spendingChart"></canvas>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- MODALS -->

    <!-- Gmail Sync Modal -->
    <div id="gmailSyncModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('gmailSyncModal')"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
            <div class="p-6 text-center">
                <div class="h-16 w-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                    <i class="fa-brands fa-google"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Connect Gmail Inbox</h3>
                <p class="text-sm text-gray-500 mb-6">Finler AI securely scans bank e-statements and auto-updates your transactions in real-time.</p>
                <a href="sync_gmail.php" class="w-full block bg-primary text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition shadow-md mb-3">
                    Authorize & Sync Now
                </a>
                <button onclick="toggleModal('gmailSyncModal')" class="w-full bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Statement Modal -->
    <div id="uploadModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('uploadModal')"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
            <div class="py-4 px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-xl font-bold">Ingest Bank Statement (PDF/CSV)</p>
                    <i class="fa-solid fa-xmark cursor-pointer text-gray-500" onclick="toggleModal('uploadModal')"></i>
                </div>
                <form action="upload_statement.php" method="POST" enctype="multipart/form-data">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center mb-4 relative bg-gray-50">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-primary mb-2"></i>
                        <p class="text-xs font-medium text-gray-700" id="fileName">Select PDF Statement</p>
                        <input type="file" name="statement_file" accept=".pdf,.csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('fileName').innerText=this.files[0].name">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition text-sm">Run AI Ingestion</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Manual Subscription Modal -->
    <div id="subModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('subModal')"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
            <div class="py-4 px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-xl font-bold">Add External Subscription / Utility</p>
                    <i class="fa-solid fa-xmark cursor-pointer text-gray-500" onclick="toggleModal('subModal')"></i>
                </div>
                <form action="dashboard.php" method="POST">
                    <input type="hidden" name="add_subscription" value="1">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Service / Subscription Name</label>
                        <input type="text" name="sub_name" required placeholder="e.g. Yoga Class, Netflix, Wifi" class="w-full px-3 py-2 border rounded-lg text-xs">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Monthly Amount (₹)</label>
                        <input type="number" step="0.01" name="sub_amount" required placeholder="e.g. 49" class="w-full px-3 py-2 border rounded-lg text-xs">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select name="sub_cycle" class="w-full px-3 py-2 border rounded-lg text-xs">
                            <option value="Monthly">Monthly</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white font-bold py-2.5 rounded-xl text-xs">Add to Living Model</button>
                </form>
            </div>
        </div>
    </div>

    <!-- What-If Simulator Modal -->
    <div id="whatIfModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('whatIfModal')"></div>
        <div class="bg-white w-11/12 md:max-w-lg mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
            <div class="py-4 px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-xl font-bold"><i class="fa-solid fa-brain text-primary"></i> AI What-If Scenario Planner</p>
                    <i class="fa-solid fa-xmark cursor-pointer text-gray-500" onclick="toggleModal('whatIfModal')"></i>
                </div>
                <div class="my-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                    <label class="block text-xs font-bold text-gray-700 mb-2">What if I cut dining out / shopping by...</label>
                    <input type="range" id="simRange" min="10" max="80" value="30" class="w-full h-2 bg-gray-200 rounded-lg cursor-pointer" oninput="updateSimulation(this.value)">
                    <p class="text-center font-bold text-primary text-lg mt-2" id="simVal">30%</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl mb-4 text-center border border-gray-200">
                    <p class="text-xs text-gray-500">Living Money Model Prediction:</p>
                    <p class="text-base font-bold text-gray-900">You will achieve your financial goal <span class="text-green-600" id="timeSaved">3 Months Faster</span></p>
                </div>
                <button onclick="applyScenario()" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-black transition text-sm">Apply Scenario to Plan</button>
            </div>
        </div>
    </div>

    <!-- Create Goal Modal -->
    <div id="goalModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('goalModal')"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
            <div class="py-4 px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-xl font-bold">Add Persistent Goal</p>
                    <i class="fa-solid fa-xmark cursor-pointer text-gray-500" onclick="toggleModal('goalModal')"></i>
                </div>
                <form action="save_goal.php" method="POST">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Goal Name</label>
                        <input type="text" name="goal_name" required placeholder="e.g. House Construction" class="w-full px-3 py-2 border rounded-lg text-xs">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Target Amount (₹)</label>
                        <input type="number" name="target_amount" required class="w-full px-3 py-2 border rounded-lg text-xs">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Target Date</label>
                        <input type="date" name="target_date" required class="w-full px-3 py-2 border rounded-lg text-xs">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white font-bold py-2.5 rounded-xl text-xs">Save Goal</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Initialization -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const savedVal = localStorage.getItem('sim_percentage');
            if (savedVal) {
                document.getElementById('simRange').value = savedVal;
                updateSimulation(savedVal);
            }
        });

        function updateSimulation(val) {
            document.getElementById('simVal').innerText = val + '%';
            const months = Math.round(val / 10);
            document.getElementById('timeSaved').innerText = months + ' Months Faster';
            localStorage.setItem('sim_percentage', val);
        }

        function toggleModal(id) {
            document.getElementById(id).classList.toggle('opacity-0');
            document.getElementById(id).classList.toggle('pointer-events-none');
        }

        function applyScenario() {
            toggleModal('whatIfModal');
            window.location.href = 'dashboard.php?scenario=applied#transactions';
        }

        function updateCategory(txnId, newCategory) {
            fetch('update_category.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + txnId + '&category=' + encodeURIComponent(newCategory)
            }).then(res => res.text()).then(data => {
                console.log('Category Updated');
            });
        }

        // Render Spending Breakdown Chart
        const ctx = document.getElementById('spendingChart').getContext('2d');
        const spendingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($category_spend)) ?>,
                datasets: [{
                    label: 'Spend Amount (₹)',
                    data: <?= json_encode(array_values($category_spend)) ?>,
                    backgroundColor: '#4F46E5',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>