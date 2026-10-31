<?php
// transactions.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Transactions | Finler AI</title>
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
                <h1 class="text-2xl font-bold text-gray-900">All Transactions</h1>
                <p class="text-sm text-gray-500 mt-1">AI-categorized financial ledger.</p>
            </div>
            <a href="dashboard.php" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">Back to Dashboard</a>
        </header>
        <main class="flex-1 p-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-sm border-b border-gray-100">
                            <th class="pb-3 font-medium">Date</th>
                            <th class="pb-3 font-medium">Description</th>
                            <th class="pb-3 font-medium">Category</th>
                            <th class="pb-3 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $txn): ?>
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
                            <td class="py-4 text-sm text-gray-500"><?= date('M d, Y', strtotime($txn['date'])) ?></td>
                            <td class="py-4 font-medium text-gray-900"><?= htmlspecialchars($txn['description']) ?></td>
                            <td class="py-4 text-sm"><span class="bg-indigo-50 text-primary px-2 py-1 rounded-md text-xs font-bold"><?= htmlspecialchars($txn['category']) ?></span></td>
                            <td class="py-4 text-right font-bold <?= $txn['type'] == 'credit' ? 'text-green-500' : 'text-gray-900' ?>">
                                <?= $txn['type'] == 'credit' ? '+' : '-' ?>₹<?= number_format($txn['amount'], 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>