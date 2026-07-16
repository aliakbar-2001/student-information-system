<?php
include 'db.php';
if (!isset($_GET['id'])) {
    die("Student ID missing.");
}
$id = $_GET['id'];
// VULNERABLE: SQL Injection – integer concatenation
$query = "SELECT * FROM users WHERE id = $id AND role='student'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 0; border-radius: 0 0 30px 30px; margin-bottom: 30px; }
        .profile-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> SIS</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light">Back to Home</a>
            </div>
        </div>
    </nav>

    <?php if ($student): ?>
        <div class="profile-header text-center">
            <div class="container">
                <h1><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($student['full_name']); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($student['email']); ?></p>
                <span class="badge bg-light text-dark">Student</span>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="profile-card">
                        <ul class="list-unstyled">
                            <li><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></li>
                            <li><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></li>
                            <li><strong>Result:</strong> <?php echo $student['result']; ?>%</li>
                            <li><strong>Attendance:</strong> <?php echo $student['attendance']; ?>%</li>
                            <li><strong>Academic Records:</strong> <?php echo htmlspecialchars($student['academic_records']); ?></li>
                            <li><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="container mt-5"><div class="alert alert-danger">Student not found.</div></div>
    <?php endif; ?>

    <div class="footer mt-5" style="background: #2d3748; color: #cbd5e0; padding: 20px 0; text-align: center;">
        <div class="container">
            <p class="small">&copy; 2026 SIS. Vulnerable by design.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>