<?php
// includes/footer.php
?>
    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a href="index.php" class="flex items-center mb-4">
                        <!-- Footer Logo -->
                        <img src="assets/images/logo.png" alt="Finler AI Logo" class="h-10 w-auto brightness-0 invert opacity-90" onerror="this.outerHTML='<span class=\'font-bold text-2xl tracking-tight\'>Finler <span class=\'text-secondary\'>AI</span></span>'">
                    </a>
                    <p class="text-gray-400 mb-6 max-w-sm leading-relaxed">
                        The intelligent personal finance coach with a living money model. Auto-categorize, simulate goals, and grow your wealth effortlessly.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-linkedin text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-instagram text-xl"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gray-200">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-400 hover:text-secondary transition">Features</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-secondary transition">Goal Simulator</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-secondary transition">Security & Privacy</a></li>
                        <li><a href="dashboard.php" class="text-gray-400 hover:text-secondary transition">Dashboard</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-gray-200">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-secondary transition">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-secondary transition">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-secondary transition">Contact Support</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">&copy; <?php echo date('Y'); ?> Finler AI. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar shadow effect on scroll
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>