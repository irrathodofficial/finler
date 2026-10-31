<?php
// update_category.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['category'])) {
    $txn_id = intval($_POST['id']);
    $new_cat = trim($_POST['category']);
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("UPDATE transactions SET category = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_cat, $txn_id, $user_id]);
    echo "success";
}
?>