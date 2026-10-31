<?php
// login.php
require_once 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Check if account is locked
            if ($user['locked_until'] !== null && strtotime($user['locked_until']) > time()) {
                $error = "Account locked due to multiple failed attempts. Try again later.";
            } else {
                if (password_verify($password, $user['password'])) {
                    // Reset failed attempts on success
                    $reset_stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
                    $reset_stmt->execute([$user['id']]);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    // Increment failed attempts
                    $attempts = $user['failed_attempts'] + 1;
                    $lockout_time = null;

                    if ($attempts >= 3) {
                        // Lock for 15 minutes (900 seconds)
                        $lockout_time = date('Y-m-d H:i:s', time() + 900);
                        $error = "Account locked due to 3 failed attempts. Please try again after 15 minutes.";
                    } else {
                        $error = "Invalid email or password. Attempt $attempts of 3.";
                    }

                    $update_stmt = $pdo->prepare("UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?");
                    $update_stmt->execute([$attempts, $lockout_time, $user['id']]);
                }
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}

// Google Auth URL
$google_client_id = getenv('GOOGLE_CLIENT_ID');
$redirect_uri = getenv('GOOGLE_REDIRECT_URI');
$google_auth_url = "https://accounts.google.com/o/oauth2/v2/auth?scope=email%20profile&access_type=online&response_type=code&redirect_uri=".urlencode($redirect_uri)."&client_id=".$google_client_id;

require_once 'includes/header.php';
?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Welcome back</h2>
            <p class="mt-2 text-sm text-gray-600">
                Don't have an account? <a href="signup.php" class="font-medium text-primary hover:text-indigo-500">Sign up for free</a>
            </p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <p class="text-red-700 text-sm"><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <div class="mt-8 space-y-6">
            <a href="<?= htmlspecialchars($google_auth_url) ?>" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                Continue with Google
            </a>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with email</span>
                </div>
            </div>

            <form class="space-y-5" action="login.php" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm pr-10">
                        <button type="button" onclick="togglePassword('password', 'eye-icon-login')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="eye-icon-login" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-indigo-500">Forgot password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" name="login" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>