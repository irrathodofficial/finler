<?php
// index.php
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-bg relative bg-white overflow-hidden pt-20 pb-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-primary font-semibold text-xs mb-6 tracking-wide uppercase">
                <i class="fa-solid fa-sparkles"></i> Finler - Personal Finance Coach
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-dark tracking-tight mb-6 leading-tight">
                Your Money, Managed by <br />
                <span class="gradient-text">Intelligent AI Coach</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Finler AI automatically ingests bank statements, detects spending drifts, and simulates "what-if" financial scenarios so you can hit your life goals faster.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="signup.php" class="bg-primary hover:bg-indigo-700 text-white text-lg px-8 py-4 rounded-xl font-bold transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Get Started Free
                </a>
                <a href="#features" class="bg-white border-2 border-gray-200 text-gray-700 hover:border-gray-300 text-lg px-8 py-4 rounded-xl font-bold transition flex items-center justify-center gap-2">
                    Explore Features <i class="fa-solid fa-arrow-down text-gray-400 text-sm"></i>
                </a>
            </div>
            
            <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500 font-medium">
                <span class="flex items-center gap-2"><i class="fa-solid fa-check text-secondary"></i> Zero Manual Spreadsheets</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-check text-secondary"></i> In-Memory Data Privacy</span>
                <span class="flex items-center gap-2"><i class="fa-brands fa-google text-red-500"></i> Smart Bank Sync</span>
            </div>
        </div>

        <!-- Dashboard Mockup Preview -->
        <div class="mt-16 relative mx-auto w-full max-w-5xl px-4">
            <div class="bg-white rounded-2xl shadow-2xl p-3 border border-gray-200">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Finler AI Dashboard Preview" class="w-full h-auto rounded-xl border border-gray-100 shadow-sm">
            </div>
        </div>
    </div>
</section>

<!-- Core Features & Ecosystem -->
<section id="features" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Everything you need for your financial freedom</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Traditional apps just track numbers. Finler AI adapts dynamically as your life changes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-file-pdf text-primary text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">AI Statement Ingestion</h3>
                <p class="text-gray-600">Upload messy PDF or CSV bank statements. Gemini AI instantly reads, parses, and auto-categorizes every single transaction.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-brain text-secondary text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Smart Correction Loop</h3>
                <p class="text-gray-600">Fix a miscategorized expense once using our intuitive dropdown. The AI system learns your rule instantly for all future records.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-sliders text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">What-If Goal Simulator</h3>
                <p class="text-gray-600">Simulate financial changes like "What if I cut dining out by 30%?" and see how many months faster you reach your goals.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Spending Drift Nudges</h3>
                <p class="text-gray-600">Receive proactive alerts when your discretionary outflow drifts off plan, helping you rebalance your budget immediately.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-repeat text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Manual Subs & Utilities</h3>
                <p class="text-gray-600">Seamlessly add recurring bills, gym memberships, or external subscriptions to keep your total monthly spend 100% accurate.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-rose-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-file-invoice-dollar text-rose-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Monthly Audit Reports</h3>
                <p class="text-gray-600">Generate certified PDF or print-ready monthly review reports summarizing your inflows, outflows, and net savings rate.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-primary">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Take control of your financial future today</h2>
        <p class="text-indigo-100 text-lg mb-10">Join smart planners who are building their wealth with Finler AI's Living Money Model.</p>
        <a href="signup.php" class="bg-white text-primary text-lg px-8 py-4 rounded-xl font-bold transition shadow-lg hover:shadow-xl hover:bg-gray-50 inline-block">
            Create Your Free Account Now
        </a>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>