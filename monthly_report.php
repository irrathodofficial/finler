<?php
// monthly_report.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch monthly stats
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

$total_income = 0;
$total_spend = 0;
$category_breakdown = [];

foreach ($transactions as $txn) {
    $amt = floatval($txn['amount']);
    if (strtolower($txn['type']) == 'credit') {
        $total_income += $amt;
    } else {
        $total_spend += $amt;
        $cat = !empty($txn['category']) ? trim($txn['category']) : 'Other';
        if (!isset($category_breakdown[$cat])) { $category_breakdown[$cat] = 0; }
        $category_breakdown[$cat] += $amt;
    }
}
$net_savings = $total_income - $total_spend;
$savings_rate = $total_income > 0 ? round(($net_savings / $total_income) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Review Report | Finler AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4F46E5', secondary: '#10B981', dark: '#111827' } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background-color: #F9FAFB; } </style>
</head>
<body class="text-gray-800 font-sans antialiased">
    <?php require_once 'users/includes/sidebar.php'; ?>
    <div class="ml-64 flex flex-col min-h-screen">
        <header class="h-24 bg-white/80 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-8 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">AI Monthly Review Report</h1>
                <p class="text-sm text-gray-500 mt-1">Comprehensive financial health analysis and cashflow audit.</p>
            </div>
            <button onclick="window.print()" class="bg-primary text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                <i class="fa-solid fa-print mr-2"></i> Print / Export Report
            </button>
        </header>
        <main class="flex-1 p-8 max-w-5xl mx-auto w-full space-y-6">
            <!-- Executive Summary Card -->
            <div class="bg-gradient-to-r from-primary to-indigo-900 text-white p-8 rounded-3xl shadow-lg">
                <p class="text-indigo-200 text-xs uppercase tracking-wider font-bold mb-1">Executive Summary • <?= date('F Y') ?></p>
                <h2 class="text-3xl font-bold mb-4">Net Savings Rate: <?= $savings_rate ?>%</h2>
                <p class="text-sm text-indigo-100 leading-relaxed">Your living money model indicates stable cashflows. Total income for the period stands at <strong class="text-white">₹<?= number_format($total_income, 2) ?></strong> against total expenditures of <strong class="text-white">₹<?= number_format($total_spend, 2) ?></strong>.</p>
            </div>

            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium mb-1">Total Inflows (Income)</p>
                    <h3 class="text-2xl font-bold text-green-600">+₹<?= number_format($total_income, 2) ?></h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium mb-1">Total Outflows (Spend)</p>
                    <h3 class="text-2xl font-bold text-red-500">-₹<?= number_format($total_spend, 2) ?></h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium mb-1">Net Cashflow Surplus</p>
                    <h3 class="text-2xl font-bold text-primary">₹<?= number_format($net_savings, 2) ?></h3>
                </div>
            </div>

            <!-- Category Breakdown Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Category-wise Outflow Distribution</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase border-b border-gray-100">
                            <th class="pb-3 font-medium">Category</th>
                            <th class="pb-3 font-medium">Amount Spent</th>
                            <th class="pb-3 font-medium text-right">Share of Total Spend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($category_breakdown as $cat => $amt): 
                            $pct = $total_spend > 0 ? round(($amt / $total_spend) * 100, 1) : 0;
                        ?>
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-4 font-bold text-gray-800"><?= htmlspecialchars($cat) ?></td>
                            <td class="py-4 text-gray-600 font-medium">₹<?= number_format($amt, 2) ?></td>
                            <td class="py-4 text-right font-bold text-primary"><?= $pct ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- AI Guardrails & Coach Advice -->
            <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl">
                <h4 class="text-sm font-bold text-primary mb-2"><i class="fa-solid fa-shield-halved"></i> AI Guardrails & Recommendations</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Finler AI advises maintaining your current emergency runway. No specific equity or security recommendations are made in accordance with financial compliance guardrails.</p>
            </div>
        </main>
    </div>
</body>
</html>