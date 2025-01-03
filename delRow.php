<?php

//db connection
include 'conn.php';

//Check db connection
if (!$pdo) { 
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = isset($_POST['id-no-del']) ? $_POST['id-no-del'] : null;

    if ($studentId) {
        try {
            // Prepare SQL statement to delete the student
            $sql = "DELETE FROM students WHERE student_id = :studentId";
            $stmt = $pdo->prepare($sql);

            // Bind value
            $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Row deleted successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Student ID is required']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>