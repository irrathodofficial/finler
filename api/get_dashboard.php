<?php
// api/get_dashboard.php
require_once '../includes/db.php';

header('Content-Type: application/json');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;

// Fetch Transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_credits = 0;
$total_debits = 0;

foreach ($transactions as $txn) {
    $amt = floatval($txn['amount']);
    if (strtolower($txn['type']) == 'credit') {
        $total_credits += $amt;
    } else {
        $total_debits += $amt;
    }
}

$available_balance = $total_credits - $total_debits;

echo json_encode([
    "success" => true,
    "balance" => $available_balance,
    "monthly_spend" => $total_debits,
    "emergency_fund" => 150000.00,
    "transactions" => $transactions
]);