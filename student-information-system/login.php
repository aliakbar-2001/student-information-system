<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULNERABLE: SQL Injection
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'teacher') {
            header('Location: teacher_dashboard.php');
        } else {
            header('Location: student_dashboard.php');
        }
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 420px;
            width: 100%;
        }
        .login-card h2 { font-weight: 700; color: #333; }
        .login-card .form-control { border-radius: 10px; }
        .login-card .btn-primary { border-radius: 10px; padding: 12px; font-weight: 600; }
        .login-card .hint { font-size: 0.85rem; color: #888; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center"><i class="bi bi-box-arrow-in-right"></i> Login</h2>
        <p class="text-center text-muted">Welcome back!</p>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person"></i> Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-right-circle"></i> Login</button>
        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
        <p class="mt-2 text-center hint"><i class="bi bi-info-circle"></i> Hint: Try <code>admin' OR '1'='1</code></p>
    </div>
</body>
</html>