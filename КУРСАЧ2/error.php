<?php
// error.php
$error_code = $_GET['code'] ?? 'unknown';
$retry = isset($_GET['retry']);

$error_messages = [
    'db_connection' => 'Ошибка подключения к базе данных',
    'db_query' => 'Ошибка выполнения запроса',
    'access_denied' => 'Доступ запрещен',
    'not_found' => 'Страница не найдена'
];

$error_title = $error_messages[$error_code] ?? 'Неизвестная ошибка';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка - Лагерь Смена</title>
    <style>
        .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Arial', sans-serif;
        }
        
        .error-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        
        .error-title {
            color: #802923;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .error-message {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #f0f0f0;
            border-radius: 3px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: #802923;
            border-radius: 3px;
            animation: progress 10s linear;
        }
        
        .countdown {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
        }
        
        .btn-primary {
            background: #802923;
            color: white;
        }
        
        .btn-primary:hover {
            background: #671f1a;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .technical-info {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #666;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes progress {
            0% { width: 100%; }
            100% { width: 0%; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">🔄</div>
            <h1 class="error-title"><?php echo htmlspecialchars($error_title); ?></h1>
            
            <?php if ($error_code === 'db_connection' && $retry): ?>
                <div class="error-message">
                    <p>Проблемы с подключением к базе данных.</p>
                    <p>Автоматически пытаемся восстановить соединение...</p>
                </div>
                
                <div class="countdown" id="countdown">
                    Обновление через: <span id="timer">10</span> сек
                </div>
                
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                
                <div class="error-actions">
                    <a href="index.php" class="btn btn-primary">Попробовать сейчас</a>
                    <button onclick="stopAutoRefresh()" class="btn btn-secondary">Отменить автообновление</button>
                </div>
                
            <?php else: ?>
                <div class="error-message">
                    <p>Произошла непредвиденная ошибка.</p>
                </div>
                <div class="error-actions">
                    <a href="index.php" class="btn btn-primary">На главную</a>
                    <button onclick="location.reload()" class="btn btn-secondary">Обновить страницу</button>
                </div>
            <?php endif; ?>
            
            <div class="technical-info">
                Код ошибки: <?php echo htmlspecialchars($error_code); ?><br>
                Время: <?php echo date('d.m.Y H:i:s'); ?>
            </div>
        </div>
    </div>

    <script>
        <?php if ($error_code === 'db_connection' && $retry): ?>
        let countdown = 10;
        let autoRefresh = true;
        
        function updateTimer() {
            if (countdown > 0 && autoRefresh) {
                document.getElementById('timer').textContent = countdown;
                countdown--;
                setTimeout(updateTimer, 1000);
            } else if (autoRefresh) {
                // Пытаемся вернуться на главную страницу
                window.location.href = 'index.php';
            }
        }
        
        function stopAutoRefresh() {
            autoRefresh = false;
            document.querySelector('.countdown').innerHTML = 'Автообновление отменено';
            document.querySelector('.progress-fill').style.animation = 'none';
            document.querySelector('.progress-fill').style.width = '0%';
        }
        
        // Запускаем таймер
        updateTimer();
        
        // Альтернативный вариант: проверяем доступность каждые 3 секунды
        function checkDatabase() {
            fetch('index.php')
                .then(response => {
                    if (response.ok) {
                        window.location.href = 'index.php';
                    }
                })
                .catch(error => {
                    // База все еще недоступна, продолжаем ждать
                    console.log('База данных недоступна');
                });
        }
        
        // Проверяем каждые 3 секунды
        setInterval(checkDatabase, 3000);
        <?php endif; ?>
    </script>
</body>
</html>