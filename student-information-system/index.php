<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 0; margin-bottom: 30px; border-radius: 0 0 50px 50px; }
        .hero h1 { font-weight: 700; }
        .search-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 25px; }
        .student-card { transition: transform 0.2s; }
        .student-card:hover { transform: translateY(-5px); }
        .footer { background: #2d3748; color: #cbd5e0; padding: 30px 0; margin-top: 40px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> SIS</a>
            <div class="ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <?php if ($_SESSION['role'] == 'teacher'): ?>
                        <a href="teacher_dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
                    <?php else: ?>
                        <a href="student_dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
                    <?php endif; ?>
                    <a href="profile.php" class="btn btn-outline-light me-2">Profile</a>
                    <a href="logout.php" class="btn btn-outline-light">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light">Login</a>
                    <a href="register.php" class="btn btn-outline-light">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="hero text-center">
        <div class="container">
            <h1><i class="bi bi-mortarboard-fill"></i> Student Information System</h1>
            <p class="lead">Search for students by name or course</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="search-card">
                    <h4 class="mb-3"><i class="bi bi-search"></i> Find a Student</h4>
                    <form method="GET" action="index.php" class="d-flex">
                        <input type="text" name="q" class="form-control form-control-lg me-2" placeholder="Enter student name or course..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-search"></i> Search</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <?php
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = $_GET['q'];
            // VULNERABLE: Reflected XSS – directly echoing input
            echo '<div class="alert alert-info mt-3"><i class="bi bi-info-circle"></i> You searched for: ' . $q . '</div>';

            // VULNERABLE: SQL Injection in search query
            $sql = "SELECT * FROM users WHERE role='student' AND (full_name LIKE '%$q%' OR course LIKE '%$q%')";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                echo '<div class="row mt-4">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card student-card h-100">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title"><i class="bi bi-person-circle"></i> ' . htmlspecialchars($row['full_name']) . '</h5>';
                    echo '<p class="card-text"><i class="bi bi-envelope"></i> ' . htmlspecialchars($row['email']) . '<br>';
                    echo '<i class="bi bi-book"></i> ' . htmlspecialchars($row['course']) . '<br>';
                    echo '<i class="bi bi-award"></i> Result: ' . htmlspecialchars($row['result']) . '%</p>';
                    echo '<a href="student.php?id=' . $row['id'] . '" class="btn btn-outline-primary btn-sm">View Profile</a>';
                    echo '</div></div></div>';
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning mt-3"><i class="bi bi-exclamation-triangle"></i> No students found.</div>';
            }
        }
        ?>
    </div>

    <div class="footer">
        <div class="container text-center">
            <p>&copy; 2026 Student Information System. Vulnerable by design for educational purposes.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>