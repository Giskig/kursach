<?php
// Настройки сессии ДО session_start()
ini_set('session.gc_maxlifetime', 300); // 5 минут для теста
session_set_cookie_params([
    'lifetime' => 300, // 5 минут для теста
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Теперь запускаем сессию
session_start();


// Обновляем время последней активности
$_SESSION['LAST_ACTIVITY'] = time();

$host = '127.0.0.1:3306';
$dbname = 'smena';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
} catch(PDOException $e) {
    logError("Database connection failed: " . $e->getMessage());
    header('Location: error.php?code=db_connection');
    exit;
}

function logError($message) {
    file_put_contents('error.log', date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

?>