<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle comment posting (Stored XSS)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    // No escaping – stored XSS
    $insert = "INSERT INTO comments (user_id, comment) VALUES ($user_id, '$comment')";
    mysqli_query($conn, $insert);
    header('Location: student_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .dashboard-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 0; border-radius: 0 0 30px 30px; margin-bottom: 30px; }
        .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .comment-box { background: white; border-radius: 10px; padding: 15px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-mortarboard-fill"></i> SIS</a>
            <div class="ms-auto">
                <span class="navbar-text text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="profile.php" class="btn btn-outline-light me-2">Profile</a>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="dashboard-header text-center">
        <div class="container">
            <h1><i class="bi bi-speedometer2"></i> Student Dashboard</h1>
            <p class="lead">Your information and community</p>
        </div>
    </div>

    <div class="container">
        <!-- DOM XSS: reads hash from URL -->
        <div id="message" class="alert alert-info"></div>
        <script>
            var hash = window.location.hash.substring(1);
            if (hash) {
                document.getElementById('message').innerHTML = hash;
            } else {
                document.getElementById('message').innerHTML = 'Welcome to your dashboard!';
            }
        </script>

        <div class="row">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5><i class="bi bi-person"></i> My Profile</h5>
                    <ul class="list-unstyled">
                        <li><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></li>
                        <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                        <li><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></li>
                        <li><strong>Result:</strong> <?php echo $user['result']; ?>%</li>
                        <li><strong>Attendance:</strong> <?php echo $user['attendance']; ?>%</li>
                        <li><strong>Academic Records:</strong> <?php echo htmlspecialchars($user['academic_records']); ?></li>
                        <li><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></li>
                    </ul>
                    <a href="student.php?id=<?php echo $user_id; ?>" class="btn btn-primary">View Public Profile</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5><i class="bi bi-chat-dots"></i> Discussion</h5>
                    <form method="POST" action="student_dashboard.php">
                        <div class="mb-2">
                            <textarea name="comment" class="form-control" rows="2" placeholder="Post a comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Post</button>
                    </form>
                    <hr>
                    <h6>Recent Comments</h6>
                    <?php
                    $comments_query = "SELECT comments.*, users.username FROM comments LEFT JOIN users ON comments.user_id = users.id ORDER BY posted_at DESC LIMIT 10";
                    $comments_result = mysqli_query($conn, $comments_query);
                    if ($comments_result && mysqli_num_rows($comments_result) > 0) {
                        while ($row = mysqli_fetch_assoc($comments_result)) {
                            // VULNERABLE: Stored XSS – direct echo of comment
                            echo '<div class="comment-box">';
                            echo '<div class="comment-body">' . $row['comment'] . '</div>';
                            echo '<div class="comment-meta small text-muted">— ' . htmlspecialchars($row['username'] ?? 'Anonymous') . ' at ' . $row['posted_at'] . '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted">No comments yet.</p>';
                    }
                    ?>
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