<?php
// auth.php
require_once 'includes/db.php';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$google_client_id = getenv('GOOGLE_CLIENT_ID');
$google_client_secret = getenv('GOOGLE_CLIENT_SECRET');
$redirect_uri = getenv('GOOGLE_REDIRECT_URI'); // This should be http://localhost/finler/auth.php

// Check if we have an authorization code from Google
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // 1. Exchange the authorization code for an access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $post_data = [
        'code' => $code,
        'client_id' => $google_client_id,
        'client_secret' => $google_client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    // 2. If we got the access token, fetch user profile data
    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];
        
        $profile_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $profile_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        $profile_response = curl_exec($ch);
        curl_close($ch);

        $profile_data = json_decode($profile_response, true);

        if (isset($profile_data['email'])) {
            $google_id = $profile_data['id'];
            $name = $profile_data['name'];
            $email = $profile_data['email'];

            // 3. Check if user already exists in our database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // User exists, just log them in
                // Also update google_id if it's empty
                if (empty($user['google_id'])) {
                    $update_stmt = $pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                    $update_stmt->execute([$google_id, $user['id']]);
                }
                
                // Reset failed attempts if any
                $reset_stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
                $reset_stmt->execute([$user['id']]);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

            } else {
                // User doesn't exist, create a new account
                // Generate a random secure password for Google sign-ups (since they don't enter one)
                $random_password = bin2hex(random_bytes(10)); 
                $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);

                $insert_stmt = $pdo->prepare("INSERT INTO users (name, email, password, google_id) VALUES (?, ?, ?, ?)");
                if ($insert_stmt->execute([$name, $email, $hashed_password, $google_id])) {
                    // Log the new user in
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                } else {
                    die("Database error during registration.");
                }
            }
            
            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
            
        } else {
            die("Could not retrieve email from Google.");
        }
    } else {
        die("Error exchanging code for token. " . print_r($token_data, true));
    }
} else {
    // If someone visits auth.php directly without a code
    header("Location: login.php");
    exit();
}
?>