<?php
require_once 'db_conf.php';
require_once 'functions.php';

$submittedTime = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
    if (saveSurveyResponse($pdo, $_POST)) $submittedTime = date('H:i:s d.m.Y');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Опитування: Напрямки в IT</title>

    <style>
        * { box-sizing: border-box; }
        body {
            background-color: #222; font-family: 'Segoe UI', system-ui, sans-serif;
            color: #AAA; margin: 0; line-height: 1.6;
        }
        .container { max-width: 30rem; margin: 1.25rem auto; padding: 0 1rem; }
        .container form > * { display: block; width: 100%; }
    </style>
    
</head>
<body>
    <h2 style="max-width: 30rem; margin: 1rem auto; padding: 0 1rem; text-align: left;">
        Опитування: Напрямки в IT
    </h2>

    <?php if ($submittedTime): ?>
        <p style="margin: 1rem auto; color: green;">Відповідь збережено о: <?php echo $submittedTime; ?> ✓</p>
    <?php endif; ?>

    <form method="POST" class="container">
        <label>Ваше ім'я:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>1. Ваш вік:</label><br>
        <input type="number" name="age" min="16" max="130" required><br>

        <p>2. Який напрямок CS вам найбільш цікавий?</p>
        <select name="field">
            <option value="Backend">Backend Development</option>
            <option value="Frontend">Frontend Development</option>
            <option value="Mobile">Fullstack + Mobile</option>
            <option value="DataScience">Data Science</option>
            <option value="NetworkingAndCloudEngineering">Networking & Cloud Engineering</option>
            <option value="Cybersecurity">Cybersecurity</option>
            <option value="AI/ML">AI & Machine Learning</option>
            <option value="Other">Other</option>
        </select>

        <p>3. Чому ви обрали саме цей напрямок?</p>
        <textarea name="reason" rows="4" style="width:100%"></textarea><br><br>

        <button type="submit">Надіслати анкету</button>
    </form>
</body>
</html>
