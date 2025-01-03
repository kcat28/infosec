<?php
include 'conn.php'; // Include database connection

// Check for database connection
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'editRow') {
    // Prepare the data from POST
    $studentId = isset($_POST['edit-id-no']) ? $_POST['edit-id-no'] : null;
    $studentNum = isset($_POST['edit-student-no']) ? $_POST['edit-student-no'] : null;
    $studentName = isset($_POST['edit-fullname']) ? $_POST['edit-fullname'] : null;
    $course = isset($_POST['edit-course']) ? $_POST['edit-course'] : null;

    // Collect subcomponent scores
    $subcomponents = [];
    for ($i = 1; $i <= 11; $i++) {
        $key = "edit-subcomponent$i";
        if (isset($_POST[$key])) {
            $subcomponents[] = $_POST[$key]; // Add the value to the array
        } else {
            $subcomponents[] = null; // Fallback to null if missing
        }
    }

    if ($studentId && $studentNum && $studentName && $course) {
        try {
            // Update the students table
            $sql = "UPDATE students 
                    SET student_num = :studentNum, fullname = :studentName, course = :course 
                    WHERE student_id = :studentId";
            $stmt = $pdo->prepare($sql);

            // Bind values
            $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->bindValue(':studentNum', $studentNum, PDO::PARAM_STR);
            $stmt->bindValue(':studentName', $studentName, PDO::PARAM_STR);
            $stmt->bindValue(':course', $course, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            // Update the scores table
            $sqlScores = "
                UPDATE scores 
                SET subcompscores1 = :subcompscores1, subcompscores2 = :subcompscores2, subcompscores3 = :subcompscores3, 
                subcompscores4 = :subcompscores4, subcompscores5 = :subcompscores5, subcompscores6 = :subcompscores6, 
                subcompscores7 = :subcompscores7, subcompscores8 = :subcompscores8, subcompscores9 = :subcompscores9, 
                subcompscores10 = :subcompscores10, subcompscores11 = :subcompscores11
                WHERE student_id = :studentId";
            $stmt = $pdo->prepare($sqlScores);

            // Bind student_id
            $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);

            // Bind each subcomponent score dynamically
            for ($i = 1; $i <= 11; $i++) {
                $stmt->bindValue(":subcompscores$i", $subcomponents[$i - 1], PDO::PARAM_STR); // Adjust type if needed
            }

            // Execute the statement
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Row updated successfully']);
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
