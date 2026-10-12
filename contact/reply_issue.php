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
// reply_issue.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueID = $_POST['issue_id'];
    $adminReply = $_POST['admin_reply'];

    // Update issue status and reply in the database
    $pdo = new PDO("mysql:host=localhost;dbname=sywzieht_777", "sywzieht_777", "sywzieht_777");
    $stmt = $pdo->prepare("UPDATE issues SET admin_reply = :admin_reply, status = 'Replied' WHERE id = :id");
    $stmt->bindParam(':admin_reply', $adminReply);
    $stmt->bindParam(':id', $issueID);
    $stmt->execute();

    // Redirect back to the issue details page
    header("Location: view_issue.php?id=" . $issueID);
}
?>
