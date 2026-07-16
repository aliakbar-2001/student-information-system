<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header('Location: login.php');
    exit;
}
include 'db.php';

// Handle add student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $result = $_POST['result'];
    $attendance = $_POST['attendance'];
    $academic_records = $_POST['academic_records'];

    $query = "INSERT INTO users (username, password, email, role, full_name, address, course, result, attendance, academic_records)
              VALUES ('$username', '$password', '$email', 'student', '$full_name', '$address', '$course', '$result', '$attendance', '$academic_records')";
    mysqli_query($conn, $query);
    header('Location: teacher_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .dashboard-header { background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; padding: 30px 0; border-radius: 0 0 30px 30px; margin-bottom: 30px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> SIS</a>
            <div class="ms-auto">
                <span class="navbar-text text-white me-3">Teacher: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="profile.php" class="btn btn-outline-light me-2">Profile</a>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="dashboard-header text-center">
        <div class="container">
            <h1><i class="bi bi-speedometer2"></i> Teacher Dashboard</h1>
            <p class="lead">Manage student records</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-3">
                    <h5><i class="bi bi-people"></i> Student List</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM users WHERE role='student'";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['course']) . '</td>';
                                echo '<td>' . $row['result'] . '%</td>';
                                echo '<td>
                                        <a href="student.php?id=' . $row['id'] . '" class="btn btn-sm btn-info">View</a>
                                        <a href="edit_student.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_student.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this student?\')">Delete</a>
                                      </td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5><i class="bi bi-person-plus"></i> Add Student</h5>
                    <form method="POST" action="teacher_dashboard.php">
                        <div class="mb-2">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-2">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-2">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="address" class="form-control" placeholder="Address">
                        </div>
                        <div class="mb-2">
                            <input type="text" name="course" class="form-control" placeholder="Course" required>
                        </div>
                        <div class="mb-2">
                            <input type="number" step="0.01" name="result" class="form-control" placeholder="Result (%)">
                        </div>
                        <div class="mb-2">
                            <input type="number" name="attendance" class="form-control" placeholder="Attendance (%)">
                        </div>
                        <div class="mb-2">
                            <textarea name="academic_records" class="form-control" placeholder="Academic Records"></textarea>
                        </div>
                        <button type="submit" name="add_student" class="btn btn-primary w-100">Add Student</button>
                    </form>
                </div>
            </div>
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