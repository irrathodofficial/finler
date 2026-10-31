<?php
// sync_gmail.php
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Simulated Bank Transaction Data (As if fetched from Gmail)
$mock_transactions = [
    ['date' => date('Y-m-d', strtotime('-1 days')), 'desc' => 'Zomato - Online Order', 'amount' => 450.00, 'type' => 'debit', 'category' => 'Food'],
    ['date' => date('Y-m-d', strtotime('-2 days')), 'desc' => 'Netflix Subscription', 'amount' => 199.00, 'type' => 'debit', 'category' => 'Entertainment'],
    ['date' => date('Y-m-d', strtotime('-3 days')), 'desc' => 'Uber Ride', 'amount' => 320.00, 'type' => 'debit', 'category' => 'Transport'],
    ['date' => date('Y-m-d', strtotime('-5 days')), 'desc' => 'Salary Credit - RapidEzy', 'amount' => 75000.00, 'type' => 'credit', 'category' => 'Income'],
    ['date' => date('Y-m-d', strtotime('-6 days')), 'desc' => 'Amazon - Groceries', 'amount' => 1250.00, 'type' => 'debit', 'category' => 'Shopping'],
];

// Check if user already has transactions
$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$count = $stmt->fetchColumn();

// Only insert if no transactions exist (to prevent duplicates on multiple clicks)
if ($count == 0) {
    foreach ($mock_transactions as $txn) {
        $insert_stmt = $pdo->prepare("INSERT INTO transactions (user_id, date, description, amount, type, category) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->execute([$user_id, $txn['date'], $txn['desc'], $txn['amount'], $txn['type'], $txn['category']]);
    }
}

// Redirect back to dashboard with a success flag
header("Location: dashboard.php?sync=success");
exit();
?>