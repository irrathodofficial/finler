<?php
// index.php
require_once 'includes/header.php';
?>

<section class="hero-bg relative bg-white overflow-hidden pt-20 pb-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-extrabold text-dark tracking-tight mb-6 leading-tight">
                Your Money, Managed by <br />
                <span class="gradient-text">Intelligent AI</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Finler AI automatically syncs your bank statements, categorizes expenses, and simulates "what-if" scenarios so you can hit your financial goals faster.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="signup.php" class="bg-primary hover:bg-indigo-700 text-white text-lg px-8 py-4 rounded-xl font-bold transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Start Your Free Trial
                </a>
                <a href="#features" class="bg-white border-2 border-gray-200 text-gray-700 hover:border-gray-300 text-lg px-8 py-4 rounded-xl font-bold transition flex items-center justify-center gap-2">
                    See How It Works <i class="fa-solid fa-play text-gray-400 text-sm"></i>
                </a>
            </div>
            
            <div class="mt-8 flex items-center justify-center gap-6 text-sm text-gray-500 font-medium">
                <span class="flex items-center gap-2"><i class="fa-solid fa-check text-secondary"></i> Zero Manual Entry</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-check text-secondary"></i> Bank-Level Security</span>
                <span class="flex items-center gap-2"><i class="fa-brands fa-google text-red-500"></i> Gmail Auto-Sync</span>
            </div>
        </div>

        <!-- Dashboard Mockup Image - FIX APPLIED HERE -->
        <div class="mt-20 relative mx-auto w-full max-w-5xl px-4">
            <div class="bg-white rounded-2xl shadow-2xl p-3 border border-gray-200">
                <!-- Removed max-height and object-cover, added w-full h-auto so it scales perfectly without cutting -->
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Finler AI Dashboard Preview" class="w-full h-auto rounded-xl border border-gray-100 shadow-sm">
            </div>
        </div>
    </div>
</section>

<!-- How It Works & Features -->
<section id="features" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">A complete ecosystem for your wealth</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">We replace messy spreadsheets with an AI coach that understands your life.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-brands fa-google text-primary text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Auto-Sync via Gmail</h3>
                <p class="text-gray-600">Connect securely. Finler AI reads your bank statement PDFs and transaction emails automatically. No manual typing required.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-sliders text-secondary text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">"What-If" Goal Simulator</h3>
                <p class="text-gray-600">Ask the AI: "What if I cut dining out by 30%?" See exactly how it speeds up your goal to buy a house or a car.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-6">
                    <i class="fa-solid fa-brain text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">AI Correction Loop</h3>
                <p class="text-gray-600">Fix a miscategorized transaction once. Our AI learns your specific rules and categorizes future expenses flawlessly.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-primary">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Take control of your financial future today</h2>
        <p class="text-indigo-100 text-lg mb-10">Join thousands of users who are growing their wealth with Finler AI.</p>
        <a href="signup.php" class="bg-white text-primary text-lg px-8 py-4 rounded-xl font-bold transition shadow-lg hover:shadow-xl hover:bg-gray-50 inline-block">
            Create Your Free Account
        </a>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>