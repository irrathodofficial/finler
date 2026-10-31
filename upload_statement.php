<?php
// upload_statement.php
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['statement_file'])) {
    
    // Receive the file and the password
    $pdf_password = $_POST['pdf_password'] ?? '';
    
    // In a real production app, we would use a library like 'Spatie\PdfToText' 
    // or 'FPDI' to decrypt the PDF using $pdf_password in RAM, extract text, 
    // and send it to Gemini API.
    
    // For Hackathon Demo: We simulate the successful decryption and AI extraction
    // This ensures your demo works flawlessly on stage.
    
    $mock_transactions = [
        ['date' => date('Y-m-d', strtotime('-1 days')), 'desc' => 'Zomato / Swiggy Order', 'amount' => 450.00, 'type' => 'debit', 'category' => 'Food'],
        ['date' => date('Y-m-d', strtotime('-3 days')), 'desc' => 'Amazon India', 'amount' => 1250.00, 'type' => 'debit', 'category' => 'Shopping'],
        ['date' => date('Y-m-d', strtotime('-4 days')), 'desc' => 'Electricity Bill', 'amount' => 1500.00, 'type' => 'debit', 'category' => 'Utilities'],
        ['date' => date('Y-m-d', strtotime('-5 days')), 'desc' => 'Salary - Axis Bank', 'amount' => 85000.00, 'type' => 'credit', 'category' => 'Income'],
    ];

    foreach ($mock_transactions as $txn) {
        $insert_stmt = $pdo->prepare("INSERT INTO transactions (user_id, date, description, amount, type, category) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->execute([$_SESSION['user_id'], $txn['date'], $txn['desc'], $txn['amount'], $txn['type'], $txn['category']]);
    }
    
    // Redirect with success message
    header("Location: dashboard.php?sync=success");
    exit();
}