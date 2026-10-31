<?php
// export_pdf.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$filter_category = isset($_GET['category']) ? trim($_GET['category']) : '';

if (!empty($filter_category)) {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND category = ? ORDER BY date DESC");
    $stmt->execute([$user_id, $filter_category]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
    $stmt->execute([$user_id]);
}
$transactions = $stmt->fetchAll();

// Setting headers for PDF/Print download view
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Report - Finler AI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
        h2 { color: #4F46E5; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: #111827; }
        .credit { color: #10B981; font-weight: bold; }
        .debit { color: #EF4444; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <h2>Finler AI - Certified Financial Statement Report</h2>
    <p><strong>User ID:</strong> <?= $user_id ?></p>
    <p><strong>Filter Category:</strong> <?= !empty($filter_category) ? htmlspecialchars($filter_category) : 'All Categories' ?></p>
    <p><strong>Generated On:</strong> <?= date('Y-m-d H:i:s') ?></p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Category</th>
                <th>Type</th>
                <th>Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transactions as $txn): ?>
            <tr>
                <td><?= $txn['date'] ?></td>
                <td><?= htmlspecialchars($txn['description']) ?></td>
                <td><?= htmlspecialchars($txn['category']) ?></td>
                <td><?= ucfirst($txn['type']) ?></td>
                <td class="<?= $txn['type'] == 'credit' ? 'credit' : 'debit' ?>">
                    <?= $txn['type'] == 'credit' ? '+' : '-' ?><?= number_format($txn['amount'], 2) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="footer">
        <p>Generated securely via Finler AI Living Money Model. Confidential Report.</p>
    </div>
</body>
</html>