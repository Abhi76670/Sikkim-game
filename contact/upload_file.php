<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $issueID = $_POST['issue_id'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    if ($fileError === 0) {
        // Move the file to the uploads directory
        $fileDestination = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        // Save file info in the database
        require 'db_connection.php';
        $stmt = $pdo->prepare("INSERT INTO issue_files (issue_id, file_name) VALUES (:issue_id, :file_name)");
        $stmt->execute(['issue_id' => $issueID, 'file_name' => $fileName]);

        header("Location: view_issue.php?id=" . $issueID);
    } else {
        echo "Error uploading the file.";
    }
}
?>
