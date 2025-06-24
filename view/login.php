<?php
session_start();
require_once '../controller/adminController.php';
require_once '../controller/clientController.php';

$message = '';
$show_register = isset($_GET['register']); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $adminController = new ControllerAdmin();
        $clientController = new ControllerClient();

        if ($adminController->authLogin($username, $password)) {
            header("Location: ../view/adminDashboard.php");
            exit;
        }

        $clientData = $clientController->authLogin($username, $password);
        if ($clientData) {
            $_SESSION['role'] = 'client';
            $_SESSION['username'] = $clientData['username'];
            $_SESSION['user_name'] = $clientData['nama_client'];
            $_SESSION['user_id'] = $clientData['id_client'];
            $_SESSION['id_client'] = $clientData['id_client']; 
            header("Location: ../view/clientDashboard.php");
            exit;
        }

        $message = "Username atau password salah!";

    } elseif ($action === 'register') {
        $data = [
            'nama_client' => trim($_POST['nama_client']),
            'email' => trim($_POST['email']),
            'no_hp' => trim($_POST['no_hp']),
            'alamat' => trim($_POST['alamat']),
            'username' => trim($_POST['username']),
            'password' => trim($_POST['password'])
        ];

        $clientController = new ControllerClient();

        if ($clientController->authRegister($data)) {
            $message = "Registrasi berhasil! Silakan login.";
        } else {
            $message = "Registrasi gagal. Username mungkin sudah digunakan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $show_register ? 'Register' : 'Login' ?> | Royal Beauty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ffffff;
            --accent-color: #967E76;
            --light-accent: rgba(232, 59, 142, 0.1);
            --text-color: #333333;
            --light-text: #777777;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --overlay-color: rgba(0, 0, 0, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', 'Helvetica Neue', Arial, sans-serif;
            background: url('../image/loginbaru.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            color: var(--text-color);
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--overlay-color);
            z-index: 0;
        }

        /* Floating Elements Animation */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            opacity: 0.7;
            animation: float 15s infinite linear;
            font-size: 24px;
            color: rgba(255, 255, 255, 0.7);
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }

        /* Pulse Animation for Logo */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .top-nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .logo {
            height: 150px;
            width: auto;
            filter: brightness(0) invert(1);
            animation: pulse 4s ease-in-out infinite;
        }

        .language-switcher {
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .language-switcher:hover {
            color: var(--accent-color);
            transform: translateY(-2px);
        }

        /* Hero Text Animation */
        .hero-text {
            position: absolute;
            bottom: 80px;
            left: 40px;
            color: white;
            z-index: 5;
            max-width: 500px;
            transform: translateX(-20px);
            opacity: 0;
            animation: slideInLeft 1s 0.5s forwards;
        }

        @keyframes slideInLeft {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .hero-text h1 {
            font-size: 42px;
            font-weight: 300;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 16px;
            font-weight: 300;
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Form Container Animation */
        .form-container {
            width: 100%;
            max-width: 400px;
            background: var(--secondary-color);
            padding: 40px;
            position: relative;
            z-index: 5;
            margin-left: auto;
            margin-right: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.8s 0.3s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Button Ripple Effect */
        button {
            width: 100%;
            padding: 15px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }

        button:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        button:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        /* Input Focus Animation */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(150, 126, 118, 0.2);
            transform: translateY(-1px);
        }

        /* Message Animation */
        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Link Hover Animation */
        .link-register a,
        .link-login a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .link-register a::after,
        .link-login a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .link-register a:hover::after,
        .link-login a:hover::after {
            width: 100%;
        }

        /* Rest of your existing styles... */
        h2 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 30px;
            text-align: center;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--light-text);
        }

        .link-register,
        .link-login {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--light-text);
        }

        .message.success {
            background-color: var(--light-accent);
            color: var(--accent-color);
            border-left: 3px solid var(--accent-color);
        }

        .message.error {
            background-color: #fdecea;
            color: var(--error-color);
            border-left: 3px solid var(--error-color);
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-login p {
            font-size: 12px;
            color: var(--light-text);
            margin-bottom: 15px;
            position: relative;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e0e0e0;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--light-accent);
            color: var(--accent-color);
            border-color: var(--accent-color);
            transform: translateY(-3px) scale(1.1);
        }

        @media (max-width: 768px) {
            .hero-text {
                display: none;
            }
            
            .form-container {
                margin: 0 auto;
                max-width: 90%;
                padding: 30px;
            }
            
            .top-nav {
                padding: 15px 20px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 25px 20px;
            }
            
            h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }
            
            button {
                padding: 12px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<!-- Floating Elements -->
<div class="floating-elements">
    <div class="floating-element" style="top: 15%; left: 10%;">✧</div>
    <div class="floating-element" style="top: 25%; right: 15%; animation-delay: 2s;">✦</div>
    <div class="floating-element" style="bottom: 20%; left: 20%; animation-delay: 4s;">❀</div>
    <div class="floating-element" style="top: 70%; right: 10%; animation-delay: 1s;">✿</div>
</div>

<!-- Top Navigation -->
<nav class="top-nav">
    <img src="..\image\logo.png" alt="Royal Beauty" class="logo">
    <div class="language-switcher">ID | EN</div>
</nav>

<!-- Hero Text -->
<div class="hero-text">
    <h1>Discover Your Royal Beauty</h1>
    <p>Experience premium beauty treatments with our expert stylists and aestheticians. Your journey to radiant beauty begins here.</p>
</div>

<!-- Form Container -->
<div class="form-container">
    <?php if (!$show_register): ?>
        <h2>Holla!</h2>

        <?php if (!empty($message)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Your username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Your password">
            </div>
            <button type="submit">Sign In</button>
        </form>

        <div class="link-register">
            Not a member? <a href="?register=true">Join now</a>
        </div>

    <?php else: ?>
        <h2>Create Account</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'berhasil') !== false ? 'success' : 'error' ?>">
                <i class="fas <?= strpos($message, 'berhasil') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="nama_client" required placeholder="Your full name">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Your email address">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="no_hp" required placeholder="Your phone number">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="alamat" required placeholder="Your address">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Choose a username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a password">
            </div>
            <button type="submit">Register Now</button>
        </form>

        <div class="link-login">
            Already have an account? <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">Sign in</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>