<?php
header('Content-Type: application/json');  // This is crucial
include 'db.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Invalid request method");
    }

    // Get and sanitize input data
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $objective = $conn->real_escape_string($_POST['objective'] ?? '');
    $hypothesis = $conn->real_escape_string($_POST['hypothesis'] ?? '');
    $materials = $conn->real_escape_string($_POST['materials'] ?? '');
    $procedure = $conn->real_escape_string($_POST['procedure'] ?? '');
    $safety = $conn->real_escape_string($_POST['safety'] ?? '');
    $results = $conn->real_escape_string($_POST['results'] ?? '');
    $conclusion = $conn->real_escape_string($_POST['conclusion'] ?? '');

    // Validate required fields
    if (empty($title) || empty($objective) || empty($hypothesis) || 
        empty($materials) || empty($procedure)) {
        throw new Exception("All required fields must be filled");
    }

    // Using prepared statement for security
    $stmt = $conn->prepare("INSERT INTO experiments (title, objective, hypothesis, materials, `procedure`, `safety`, results, conclusion) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $title, $objective, $hypothesis, $materials, $procedure, $safety, $results, $conclusion);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Experiment saved successfully!";
        $response['id'] = $stmt->insert_id;
    } else {
        throw new Exception($stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(400);
    $response['message'] = $e->getMessage();
} finally {
    $conn->close();
    echo json_encode($response);
}
?>