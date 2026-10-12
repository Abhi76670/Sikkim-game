<?php
// Start session to check if the user is logged in
session_start();

// Check if the admin is logged in, if not redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

// Admin ID stored in session
$admin_id = $_SESSION['admin_id'];

// Rest of your code for viewing issue details...
require 'db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the 'id' parameter is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No issue ID provided.");
}

$issue_id = $_GET['id'];

// Fetch the issue details from the database
$query = "SELECT * FROM issues WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $issue_id]);
$issue = $stmt->fetch();

// If the issue does not exist, show an error
if (!$issue) {
    die("Issue not found.");
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = $_POST['reply'];
    $status = $_POST['status'];

    // Update the issue status and reply
    $updateQuery = "UPDATE issues SET reply = :reply, status = :status WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(['reply' => $reply, 'status' => $status, 'id' => $issue_id]);

    header("Location: view_issue.php?id=$issue_id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Issue</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_issues.php">View Issues</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Issue Details</h1>
        <p><strong>Account ID:</strong> <?php echo $issue['account_id']; ?></p>
        <p><strong>Issue:</strong> <?php echo $issue['issue']; ?></p>
        <p><strong>Amount Deposit:</strong> <?php echo $issue['amount_deposit']; ?></p>
        <p><strong>UTR Number:</strong> <?php echo $issue['utr_number']; ?></p>
        <p><strong>Withdrawal Order Number:</strong> <?php echo $issue['withdrawal_order_number']; ?></p>
        <p><strong>Withdrawal Amount:</strong> <?php echo $issue['withdrawal_amount']; ?></p>
        <p><strong>Deposit Proof:</strong> <?php echo $issue['deposit_proof']; ?></p>
        <p><strong>Status:</strong> <?php echo $issue['status']; ?></p>
        <p><strong>Admin Reply:</strong> <?php echo $issue['reply']; ?></p>

        <h3>Reply to Issue</h3>
        <form method="POST">
            <label for="reply">Admin Reply:</label>
            <textarea name="reply" id="reply" required><?php echo $issue['reply']; ?></textarea><br>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Pending" <?php echo ($issue['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Resolved" <?php echo ($issue['status'] === 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
                <option value="In Progress" <?php echo ($issue['status'] === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
            </select><br>

            <button type="submit">Submit Reply</button>
        </form>
    </div>
</body>
</html>
