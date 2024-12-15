<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

include 'conn.php';

/**
 * Function to calculate grades based on subcomponent scores and weights
 *
 * @param array $student_nums Array of student numbers
 * @param object $db Database connection object
 * @param int $gradingsystem_id Grading system ID
 * @return array $grades Array of grades for each student
 */
function calculateGrades($student_nums, $db, $gradingsystem_id) {
    // Initialize an array to store grades for each student
    $grades = [];

    // Step 1: Get the weights for each component from the database (fetch only once)
    $weights_query = "SELECT weight1, weight2, weight3, weight4 FROM components_weights WHERE gradingsystem_id = :gradingsystem_id";
    $stmt = $db->prepare($weights_query);
    $stmt->execute(['gradingsystem_id' => $gradingsystem_id]);
    $component_weights = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch weights once for the grading system

    if (!$component_weights) {
        return ['error' => 'Grading system not found.'];
    }

    // Step 2: Loop through each student to calculate their grade
    foreach ($student_nums as $student_num) {
        // Get the scores for the student from the database
        $scores_query = "SELECT * FROM scores WHERE student_num = :student_num";
        $stmt = $db->prepare($scores_query);
        $stmt->execute(['student_num' => $student_num]);
        $student_scores = $stmt->fetch(PDO::FETCH_ASSOC);

        // Initialize total score for the student
        $total_score = 0;

        // Ensure all required subcomponents are available
        if ($student_scores) {
            // Component 1: Subcomponent 1 & 2
            $component1_score = (($student_scores['subcompscores1'] / 60) + ($student_scores['subcompscores2'] / 60)) / 2;
            $total_score += $component_weights['weight1'] * $component1_score;

            // Component 2: Subcomponent 3, 4 & 5
            $component2_score = (($student_scores['subcompscores3'] / 60) + ($student_scores['subcompscores4'] / 60) + ($student_scores['subcompscores5'] / 60)) / 3;
            $total_score += $component_weights['weight2'] * $component2_score;

            // Component 3: Subcomponent 6, 7 & 8
            $component3_score = (($student_scores['subcompscores6'] / 60) + ($student_scores['subcompscores7'] / 60) + ($student_scores['subcompscores8'] / 60)) / 3;
            $total_score += $component_weights['weight3'] * $component3_score;

            // Component 4: Subcomponent 9, 10 & 11
            $component4_score = (($student_scores['subcompscores9'] / 60) + ($student_scores['subcompscores10'] / 60) + ($student_scores['subcompscores11'] / 60)) / 3;
            $total_score += $component_weights['weight4'] * $component4_score;

            // Calculate the final grade (percentage)
            $grade = $total_score * 100; // To get percentage
            $grade = number_format($grade, 2);  // Format grade to 2 decimal places

            // Store the grade in the grades array with student_num as the key
            $grades[$student_num] = $grade;
        } else {
            // If no scores are found for the student, mark the grade as "Not Available"
            $grades[$student_num] = "Not Available";
        }
    }

    return $grades;
}
?>
