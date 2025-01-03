<?php
include 'conn.php'; // Include database connection

// Check for database connection
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'addRow') {
    // Prepare the data from POST
    $studentId = isset($_POST['new-id-no']) ? $_POST['new-id-no'] : null;
    $studentNum = isset($_POST['new-student-no']) ? $_POST['new-student-no'] : null;
    $studentName = isset($_POST['new-fullname']) ? $_POST['new-fullname'] : null;
    $course = isset($_POST['new-course']) ? $_POST['new-course'] : null;

    // Collect subcomponent scores
    $subcomponents = [];
    for ($i = 1; $i <= 11; $i++) {
        $key = "new-subcomponent$i";
        if (isset($_POST[$key])) {
            $subcomponents[] = $_POST[$key]; // Add the value to the array
        } else {
            $subcomponents[] = null; // Fallback to null if missing
        }
    }

    if ($studentId && $studentNum && $studentName && $course) {
        try {
            // Insert into students table
            $sql = "INSERT INTO students (student_id, student_num, fullname, course) 
                    VALUES (:studentId, :studentNum, :studentName, :course)";
            $stmt = $pdo->prepare($sql);

            // Bind values
            $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->bindValue(':studentNum', $studentNum, PDO::PARAM_STR);
            $stmt->bindValue(':studentName', $studentName, PDO::PARAM_STR);
            $stmt->bindValue(':course', $course, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            // Insert into scores table
            $sqlScores = "
                INSERT INTO scores 
                (subcomp_id, student_id, subcompscores1, subcompscores2, subcompscores3, subcompscores4, subcompscores5, 
                subcompscores6, subcompscores7, subcompscores8, subcompscores9, subcompscores10, subcompscores11) 
                VALUES 
                (:subcompId, :studentId, :subcompscores1, :subcompscores2, :subcompscores3, :subcompscores4, :subcompscores5, 
                :subcompscores6, :subcompscores7, :subcompscores8, :subcompscores9, :subcompscores10, :subcompscores11)";
            $stmt = $pdo->prepare($sqlScores);

            // Bind the subcomponent ID and student ID
            $stmt->bindValue(':subcompId', 9, PDO::PARAM_INT); // Adjust subcomponent ID as needed
            $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);

            // Bind each subcomponent score dynamically
            for ($i = 1; $i <= 11; $i++) {
                $stmt->bindValue(":subcompscores$i", $subcomponents[$i - 1], PDO::PARAM_STR); // Adjust type if needed
            }

            // Execute the statement
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Row added successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
