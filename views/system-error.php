<?php
$pageTitle = "System Error";

$error_log_path = DIR . '/error.log';

if (file_exists($error_log_path) && is_readable($error_log_path)) {
    $log_content = file_get_contents($error_log_path);
    $log_content = htmlspecialchars($log_content);
} else {
    $log_content = "Error log file does not exist or cannot be read.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_error') {
            $_SESSION['message_type'] = 'danger';
            try {
                if (file_exists($error_log_path) && is_writable($error_log_path)) {
                    $file = fopen($error_log_path, 'w');
                    if ($file) {
                        fclose($file);
                        $_SESSION['message_type'] = 'success';
                        $_SESSION['message'] = "Error log has been cleared.";
                    } else {
                        $_SESSION['message'] = "Failed to open the error log file.";
                    }
                } else {
                    $_SESSION['message'] = "Error log file does not exist or is not writable.";
                }
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Todo deleted successfully.";
            } catch (Exception $e) {
                $_SESSION['message'] = "Failed to delete error. " . $e->getMessage();
            }
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0"><?= $pageTitle ?></h4>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <input type="hidden" name="action_name" value="delete_error">
                <button type="submit" class="btn btn-danger btn-sm btn-delete-record">
                    <i
                            class="ri-delete-bin-5-line align-bottom"></i> Delete
                </button>
            </form>
        </div>
        <div class="card-body">
            <?php if (!empty($log_content)) { ?>
                <div class="live-preview">
                    <pre><code class="language-log"><?= $log_content ?></code></pre>
                </div>
            <?php } else { ?>
                <h5 class="mb-0 text-muted fs-14">The error log is currently empty.</h5>
            <?php } ?>
        </div>
    </div>

<?php
ob_start();
echo "<script type='text/javascript' src='" . home_url('assets/libs/prismjs/prism.js') . "'></script>";
$additionJs = ob_get_clean();

$pageContent = ob_get_clean();
