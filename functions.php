<?php
// functions.php 

function saveSurveyResponse($pdo, $data) {
    // Saving fields to MySQL
    $sql = "INSERT INTO survey_results (name, email, age, field, reason) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['name'], 
        $data['email'], 
        $data['age'], 
        $data['field'], 
        $data['reason']
    ]);
}

function getAllResponses($pdo) {
    return $pdo->query("SELECT * FROM survey_results ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function deleteResponse($pdo, $id) {
    return $pdo->prepare("DELETE FROM survey_results WHERE id = ?")->execute([$id]);
}
?>
