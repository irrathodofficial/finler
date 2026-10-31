<?php
// dashboard.php
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch Transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

// Fetch Goals
$stmt_goals = $pdo->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY created_at DESC");
$stmt_goals->execute([$user_id]);
$goals = $stmt_goals->fetchAll();

// Calculate Stats
$total_balance = 0;
$monthly_spend = 0;
$current_month = date('m');

foreach ($transactions as $txn) {
    if ($txn['type'] == 'credit') {
        $total_balance += $txn['amount'];
    } else {
        $total_balance -= $txn['amount'];
        if (date('m', strtotime($txn['date'])) == $current_month) {
            $monthly_spend += $txn['amount'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Finler AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#4F46E5', secondary: '#10B981', dark: '#111827' } } } }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { background-color: #F9FAFB; } 
        /* Modal Animation */
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-x: hidden; overflow-y: hidden !important; }
    </style>
</head>
<body class="text-gray-800 font-sans antialiased">

    <?php require_once 'users/includes/sidebar.php'; ?>

    <div class="ml-64 flex flex-col min-h-screen">
        
        <header class="h-24 bg-white/80 backdrop-blur-md sticky top-0 z-40 flex items-center justify-between px-8 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>! 👋</h1>
                <p class="text-sm text-gray-500 mt-1">Here is your living financial model for today.</p>
            </div>
            
            <div class="flex items-center gap-5">
                <!-- Sync Dropdown -->
                <div class="relative group">
                    <button class="bg-indigo-50 text-primary px-4 py-2 rounded-lg font-semibold text-sm hover:bg-indigo-100 transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add Bank Data
                    </button>
                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="sync_gmail.php" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl border-b border-gray-50"><i class="fa-brands fa-google text-red-500 mr-2"></i> Auto-Sync via Gmail</a>
                        <button onclick="toggleModal('uploadModal')" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-b-xl"><i class="fa-solid fa-file-pdf text-red-400 mr-2"></i> Manual PDF Upload</button>
                    </div>
                </div>
                
                <div class="h-10 w-10 bg-primary text-white rounded-full flex items-center justify-center font-bold shadow-md cursor-pointer">
                    <?= strtoupper(substr($user_name, 0, 1)) ?>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8">
            
            <?php if(isset($_GET['sync']) && $_GET['sync'] == 'success'): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100">
                <i class="fa-solid fa-check-circle"></i> Bank data synced successfully! AI has categorized your transactions.
            </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['goal']) && $_GET['goal'] == 'added'): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100">
                <i class="fa-solid fa-bullseye"></i> Financial goal created successfully!
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Balance -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative">
                    <p class="text-sm font-medium text-gray-500 mb-1">Available Balance</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">₹<?= number_format($total_balance, 2) ?></h3>
                    <p class="text-sm text-green-500 font-medium">Updated just now</p>
                </div>

                <!-- Spend -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative">
                    <p class="text-sm font-medium text-gray-500 mb-1">This Month's Spend</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">₹<?= number_format($monthly_spend, 2) ?></h3>
                    <p class="text-sm text-gray-400 font-medium">Auto-categorized by AI</p>
                </div>

                <!-- AI Coach -->
                <div class="bg-gradient-to-br from-primary to-indigo-800 p-6 rounded-2xl shadow-md text-white">
                    <p class="text-indigo-200 text-sm font-medium mb-1">Finler AI Coach</p>
                    <h3 class="text-xl font-bold mb-3">Ready to simulate your future?</h3>
                    <button onclick="toggleModal('whatIfModal')" class="bg-white text-primary px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-gray-50">Run "What-If" Scenario</button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Transactions List -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Recent Transactions</h3>
                    
                    <?php if(empty($transactions)): ?>
                        <div class="text-center py-12">
                            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-file-invoice text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-gray-900 font-medium mb-2">No transactions found</h4>
                            <p class="text-gray-500 text-sm mb-4">Upload a bank statement or sync Gmail to get started.</p>
                            <button onclick="toggleModal('uploadModal')" class="bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-indigo-700">Upload PDF</button>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
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
                    <?php endif; ?>
                </div>

                <!-- Goals Widget -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Financial Goals</h3>
                    
                    <?php if(empty($goals)): ?>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center">
                            <i class="fa-solid fa-bullseye text-gray-300 text-3xl mb-3"></i>
                            <h4 class="text-gray-800 font-medium text-sm mb-1">No goals set</h4>
                            <button onclick="toggleModal('goalModal')" class="text-primary text-sm font-bold hover:underline">+ Create New Goal</button>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach($goals as $goal): ?>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-bold text-gray-800"><?= htmlspecialchars($goal['goal_name']) ?></h4>
                                    <span class="text-xs font-semibold bg-white px-2 py-1 rounded shadow-sm text-gray-600"><?= date('M Y', strtotime($goal['target_date'])) ?></span>
                                </div>
                                <p class="text-sm text-gray-500 font-medium mb-2">Target: ₹<?= number_format($goal['target_amount'], 0) ?></p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-secondary h-2 rounded-full" style="width: 25%"></div> <!-- Example Progress -->
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <button onclick="toggleModal('goalModal')" class="w-full py-2 text-primary text-sm font-bold hover:bg-indigo-50 rounded-lg transition border border-dashed border-indigo-200">+ Add Another Goal</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- MODALS SECTION -->
    
    <!-- 1. Upload Statement Modal -->
    <!-- 1. Upload Statement Modal (Updated with Password Field) -->
    <div id="uploadModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('uploadModal')"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-y-auto">
            <div class="py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold">Upload Bank Statement</p>
                    <div class="cursor-pointer z-50" onclick="toggleModal('uploadModal')">
                        <i class="fa-solid fa-xmark text-gray-500 hover:text-red-500 text-xl"></i>
                    </div>
                </div>
                <form action="upload_statement.php" method="POST" enctype="multipart/form-data">
                    <p class="text-sm text-gray-500 mb-4">Upload your bank PDF. Finler AI will securely unlock and categorize it.</p>
                    
                    <!-- File Upload Box -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer mb-4 relative bg-gray-50">
                        <i class="fa-solid fa-file-pdf text-3xl text-red-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-700" id="fileNameDisplay">Click or drag PDF to upload</p>
                        <input type="file" name="statement_file" id="statement_file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="document.getElementById('fileNameDisplay').innerText = this.files[0].name">
                    </div>

                    <!-- PDF Password Field -->
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-1">PDF Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="pdf_password" required placeholder="e.g. Your name & DOB" class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-primary focus:border-primary text-sm">
                            <i class="fa-solid fa-lock absolute right-4 top-3.5 text-gray-400"></i>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex gap-1">
                            <i class="fa-solid fa-shield-halved text-green-500"></i> 
                            <span>Secure: Password is used once for in-memory decryption and is <b>never</b> stored.</span>
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Unlock & Analyze with AI
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- 2. Create Goal Modal -->
    <div id="goalModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('goalModal')"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-y-auto">
            <div class="py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold">Create Financial Goal</p>
                    <div class="cursor-pointer z-50" onclick="toggleModal('goalModal')">
                        <i class="fa-solid fa-xmark text-gray-500"></i>
                    </div>
                </div>
                <form action="save_goal.php" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Goal Name (e.g. Buy a Car)</label>
                        <input type="text" name="goal_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Amount (₹)</label>
                        <input type="number" name="target_amount" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Date</label>
                        <input type="date" name="target_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">Set Goal</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. What-If Simulator Modal (UI for Hackathon Demo) -->
    <div id="whatIfModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal('whatIfModal')"></div>
        <div class="bg-white w-11/12 md:max-w-lg mx-auto rounded-2xl shadow-2xl z-50 overflow-y-auto">
            <div class="py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold"><i class="fa-solid fa-brain text-primary"></i> Finler AI Simulator</p>
                    <div class="cursor-pointer z-50" onclick="toggleModal('whatIfModal')">
                        <i class="fa-solid fa-xmark text-gray-500"></i>
                    </div>
                </div>
                <div class="my-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                    <label class="block text-sm font-bold text-gray-700 mb-2">What if I cut dining out by...</label>
                    <input type="range" min="0" max="100" value="30" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="document.getElementById('simValue').innerText = this.value + '%'">
                    <p class="text-center font-bold text-primary text-xl mt-2" id="simValue">30%</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl mb-4 border border-gray-200 text-center">
                    <p class="text-gray-500 text-sm">AI Prediction</p>
                    <p class="text-gray-900 font-bold">You will reach your goal <span class="text-green-600">3 Months Faster</span></p>
                </div>
                <button onclick="toggleModal('whatIfModal')" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-black transition">Apply to Plan</button>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('opacity-0');
            modal.classList.toggle('pointer-events-none');
            document.body.classList.toggle('modal-active');
        }
    </script>
</body>
</html>