<?php
// keepalive.php
require_once 'config.php';
// Простое обращение к сессии обновляет ее время
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    echo 'OK';
}
?>