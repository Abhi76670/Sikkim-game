<?php
// Dashboard page for admin showing recent issues
require 'db_connection.php';  // Assuming you have a DB connection file

// Fetching all open issues
$query = "SELECT * FROM issues WHERE status = 'Pending' ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$issues = $stmt->fetchAll();
// Start session to check if the user is logged in
session_start();

// Check if the admin is logged in, if not redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

// Admin ID stored in session
$admin_id = $_SESSION['admin_id'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_issues.php">View Issues</a></li>
            <li><a href="Pending_issues.php">Pending Issues</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Welcome, Admin</h1>
        <h2>Recent Issues</h2>
        <table>
            <thead>
                <tr>
                    <th>Issue ID</th>
                    <th>Account ID</th>
                    <th>Issue Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($issues as $issue): ?>
                <tr>
                    <td><?php echo $issue['id']; ?></td>
                    <td><?php echo $issue['account_id']; ?></td>
                    <td><?php echo $issue['issue_type']; ?></td>
                    <td><?php echo $issue['status']; ?></td>
                    <td><a href="view_issue.php?id=<?php echo $issue['id']; ?>">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
