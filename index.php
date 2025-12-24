<?php 
require_once 'db_config.php'; 
require_once 'functions.php'; 

$submittedTime = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
    if (saveSurveyResponse($pdo, $_POST)) $submittedTime = date('H:i:s d.m.Y');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title data-i18n="title">Опитування: Напрямки в IT</title>
    <script>
        dict = { uk: {"title": "Опитування: Напрямки в IT",}, en: {"title": "IT Career Survey"} }
    </script>
    
    <style>
        body { font-family: sans-serif; max-width: 40rem; margin: 1.25rem auto; line-height: 1.6; }
    </style>
</head>
<body>
    <h2>Опитування: Напрямки в IT</h2>
    
    <?php if ($submittedTime): ?>
        <p style="color: green;">Відповідь збережено о: <?php echo $submittedTime; ?> ✓</p>
    <?php endif; ?>

    <form method="POST">
        <label>Ваше ім'я:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>1. Ваш вік:</label><br>
        <input type="number" name="age" min="16" max="99" required><br>

        <p>2. Який напрямок CS вам найбільш цікавий?</p>
        <select name="field">
            <option value="Backend">Backend Development</option>
            <option value="Frontend">Frontend Development</option>
            <option value="Mobile">Fullstack + Mobile</option>
            <option value="DataScience">Data Science</option>
            <option value="NetworkingAndCloudEngineering">Networking & Cloud Engineering</option>
            <option value="Cybersecurity">Cybersecurity</option>
            <option value="AI/ML">AI & Machine Learning</option>
            <option value="Other">Інше</option>
        </select>

        <p>3. Чому ви обрали саме цей напрямок?</p>
        <textarea name="reason" rows="4" style="width:100%"></textarea><br><br>

        <button type="submit">Надіслати анкету</button>
    </form>
</body>
</html>
