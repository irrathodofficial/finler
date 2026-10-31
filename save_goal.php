<?php
// save_goal.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $goal_name = trim($_POST['goal_name']);
    $target_amount = $_POST['target_amount'];
    $target_date = $_POST['target_date'];
    
    $stmt = $pdo->prepare("INSERT INTO goals (user_id, goal_name, target_amount, target_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $goal_name, $target_amount, $target_date]);
    
    header("Location: dashboard.php?goal=added");
    exit();
}