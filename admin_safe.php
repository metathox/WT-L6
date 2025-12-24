<?php
session_start();
require_once 'db_conf.php';
require_once 'functions.php';

// Session-based login check
if (isset($_POST['login']))
    if ($_POST['user'] === 'admin' && $_POST['pass'] === 'password here')
        $_SESSION['admin_logged_in'] = true;


if (!isset($_SESSION['admin_logged_in'])): ?>
    <form method="POST">
        <input type="text" name="user" placeholder="Логін">
        <input type="password" name="pass" placeholder="Пароль">
        <button name="login">Увійти</button>
    </form>
<?php else: 
    // Delete action
    if (isset($_GET['delete_id']))
        deleteResponse($pdo, $_GET['delete_id']);

    // Displaying responses
    $results = getAllResponses($pdo);
    echo "<h1>Панель адміністратора</h1>";
    echo "<table border='1'><tr><th>Ім'я</th><th>Email</th><th>Питання 1</th><th>Дата</th><th>Дія</th></tr>";
    foreach ($results as $row) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['q1']) . "</td>
                <td>{$row['submitted_at']}</td>
                <td><a href='?delete_id={$row['id']}'>Видалити</a></td>
              </tr>";
    }
    echo "</table>";
endif; ?>
