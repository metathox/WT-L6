<?php
session_start();
require_once 'db_conf.php';
require_once 'functions.php';

// 1. Логіка входу
if (isset($_POST['login']))
    if ($_POST['user'] === 'admin' && $_POST['pass'] === 'your password here')
        $_SESSION['admin_logged_in'] = true;

// 2. Логіка видалення відповіді
if (isset($_GET['delete_id']) && isset($_SESSION['admin_logged_in'])) {
    deleteResponse($pdo, $_GET['delete_id']);
    header("Location: admin.php");
    exit;
}

// 3. Логіка експорту у CSV
if (isset($_GET['export']) && isset($_SESSION['admin_logged_in'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="survey_results.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Email', 'Age', 'Field', 'Reason', 'Date'], ",", '"', "\\");
    $data = getAllResponses($pdo);
    foreach ($data as $row) fputcsv($output, $row, ",", '"', "\\");
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Адмін-панель</title>
    <style>

        /* Загальні налаштування */

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #220022;
            color: #eee;
            margin: 0;
            padding: 2.5rem;
        }

        .container {
            max-width: 95%;
            margin: 0 auto;
        } 
        
        /* Логін блок */
        .login-box {
            max-width: 20rem;
            margin: 6.25rem auto;
            background: #252525;
            padding: 1.5625rem;
            border-radius: 0.5rem;
            border: 0.0625rem solid #444;
        }
        
        .admin-input {
            width: 100%;
            padding: 0.625rem;
            margin-bottom: 0.9375rem;
            background: #333;
            border: 0.0625rem solid #444;
            color: #fff;
            border-radius: 0.25rem;
            box-sizing: border-box;
        }

        .admin-button {
            width: 100%;
            padding: 0.625rem;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: bold;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.875rem;
            min-width: max-content;
        }
        
        /* Кнопка Вийти */
        .btn-logout {
            color: #aaa;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
        }

        .btn-logout:hover { 
            color: #ff5252;
            text-decoration: underline;
        }

        /* Таблиця */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            background: #252525;
            table-layout: auto;
        }
        
        /*  Горизонтальне центрування тексту для всіх комірок (th, td).
            Вертикальне вирівнювання по верхньому краю для зручності читання великих текстів.
        */
        .results-table th, .results-table td {
            padding: 0.9375rem;
            border: 0.0625rem solid #444;
            text-align: center;     /* Контент у центрі по ширині */
            vertical-align: top;    /* Контент притиснутий до верху клітинки */
        }

        .results-table th {
            background: #333;
            color: #e4e4e7;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        /* СПЕЦИФІЧНИЙ КЛАС ДЛЯ КОЛОНКИ З КНОПКОЮ ВИДАЛЕННЯ: Y-центр */
        .action-col { 
            vertical-align: middle !important;
        }

        /* "Причина", чому саме цей напрям.. */
        .reason-col {
            white-space: pre-wrap;
            word-wrap: break-word;
            min-width: 18.75rem;
            color: #fff;
        }
        
        /* Кнопка видалення */
        .btn-del {
            color: #ff5555;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
        }

        .btn-del:hover {
            text-decoration: underline;
            text-decoration-skip-ink: none;
        }

        /* Кнопка експорту у CSV файл */
        .btn-export {
            background: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .btn-export:hover {
            background: #218838;
        }

    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
        <div class="login-box">
            <h2 style="text-align: center;">Вхід</h2>
            <form method="POST">
                <input type="text" name="user" class="admin-input" placeholder="Логін" required>
                <input type="password" name="pass" class="admin-input" placeholder="Пароль" required>
                <button name="login" class="admin-button">Увійти</button>
            </form>
        </div>
    <?php else: ?>
        <div class="admin-header">
            <h1 style="padding: 0.5rem 1.25rem;">Відповіді користувачів</h1>
            <div>
                <a href="?export=1" class="btn-export">Експорт у CSV</a>
                <a href="logout.php" class="btn-logout">Вийти</a>
            </div>
        </div>

        <table class="results-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ім'я</th>
                    <th>Email</th>
                    <th>Вік</th>
                    <th>Напрямок</th>
                    <th>Причина (Описання)</th>
                    <th>Дата</th>
                    <th>Дія</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $results = getAllResponses($pdo); 
                foreach ($results as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['age'] ?></td>
                    <td><?= htmlspecialchars($row['field']) ?></td>
                    <td class="reason-col"><?= htmlspecialchars($row['reason']) ?></td>
                    <td><?= $row['submitted_at'] ?></td>
                    <td class="action-col">
                        <a href="?delete_id=<?= $row['id'] ?>" 
                           class="btn-del" 
                           onclick="return confirm('Видалити цей запис?')">Видалити</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
