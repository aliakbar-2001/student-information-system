<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$user = null;
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
if ($result) $user = mysqli_fetch_assoc($result);

// Handle email update – CSRF vulnerable (no token)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $new_email = $_POST['email'];
    $update = "UPDATE users SET email = '$new_email' WHERE id = $user_id";
    mysqli_query($conn, $update);
    $user['email'] = $new_email;
    $message = "Email updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 0; border-radius: 0 0 30px 30px; margin-bottom: 30px; }
        .profile-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; max-width: 600px; margin: auto; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> SIS</a>
            <div class="ms-auto">
                <span class="navbar-text text-white me-3"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="profile-header text-center">
        <div class="container">
            <h1><i class="bi bi-person-circle"></i> My Profile</h1>
            <p class="lead">View and update your information</p>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="fw-bold"><i class="bi bi-person"></i> Username</label>
                <p class="border-bottom pb-2"><?php echo htmlspecialchars($user['username']); ?></p>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><i class="bi bi-badge"></i> Role</label>
                <p class="border-bottom pb-2"><?php echo ucfirst($user['role']); ?></p>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><i class="bi bi-person-badge"></i> Full Name</label>
                <p class="border-bottom pb-2"><?php echo htmlspecialchars($user['full_name']); ?></p>
            </div>

            <h5 class="mt-4"><i class="bi bi-envelope"></i> Update Email</h5>
            <!-- CSRF vulnerability: no token, action uses POST -->
            <form method="POST" action="profile.php">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Update Email</button>
            </form>

            <p class="mt-3 text-muted small"><i class="bi bi-shield-exclamation"></i> This form is vulnerable to CSRF – an attacker can change your email without your consent.</p>
        </div>
    </div>

    <div class="footer mt-5" style="background: #2d3748; color: #cbd5e0; padding: 20px 0; text-align: center;">
        <div class="container">
            <p class="small">&copy; 2026 SIS. Vulnerable by design.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>