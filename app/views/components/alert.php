<?php

if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message_type'] ?? 'info';
    echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
        . htmlspecialchars($_SESSION['message']) .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}