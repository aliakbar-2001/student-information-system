<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $course = $_POST['course']; // for student

    // Insert (no vulnerability)
    $query = "INSERT INTO users (username, password, email, role, full_name, address, course) 
              VALUES ('$username', '$password', '$email', 'student', '$full_name', '$address', '$course')";
    if (mysqli_query($conn, $query)) {
        header('Location: login.php?registered=1');
        exit;
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 480px;
            width: 100%;
        }
        .register-card h2 { font-weight: 700; color: #333; }
        .register-card .form-control { border-radius: 10px; }
        .register-card .btn-primary { border-radius: 10px; padding: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="register-card">
        <h2 class="text-center"><i class="bi bi-person-plus"></i> Register</h2>
        <p class="text-center text-muted">Create your student account</p>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person"></i> Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person-badge"></i> Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-geo-alt"></i> Address</label>
                <input type="text" name="address" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-book"></i> Course</label>
                <input type="text" name="course" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-person-check"></i> Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>