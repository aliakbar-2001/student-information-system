<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header('Location: login.php');
    exit;
}
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: teacher_dashboard.php');
    exit;
}
$student_id = $_GET['id'];

// Fetch student data
$query = "SELECT * FROM users WHERE id = $student_id AND role='student'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
if (!$student) {
    header('Location: teacher_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $result = $_POST['result'];
    $attendance = $_POST['attendance'];
    $academic_records = $_POST['academic_records'];

    $update = "UPDATE users SET 
                full_name='$full_name', email='$email', address='$address', 
                course='$course', result='$result', attendance='$attendance', 
                academic_records='$academic_records'
                WHERE id = $student_id AND role='student'";
    mysqli_query($conn, $update);
    header('Location: teacher_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card p-4">
            <h3>Edit Student</h3>
            <form method="POST">
                <div class="mb-2">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                </div>
                <div class="mb-2">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($student['address']); ?>">
                </div>
                <div class="mb-2">
                    <label>Course</label>
                    <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($student['course']); ?>" required>
                </div>
                <div class="mb-2">
                    <label>Result (%)</label>
                    <input type="number" step="0.01" name="result" class="form-control" value="<?php echo $student['result']; ?>">
                </div>
                <div class="mb-2">
                    <label>Attendance (%)</label>
                    <input type="number" name="attendance" class="form-control" value="<?php echo $student['attendance']; ?>">
                </div>
                <div class="mb-2">
                    <label>Academic Records</label>
                    <textarea name="academic_records" class="form-control"><?php echo htmlspecialchars($student['academic_records']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Student</button>
            </form>
        </div>
    </div>
</body>
</html>