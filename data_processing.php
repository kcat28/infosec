<?php
// header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

include 'conn.php'; // Include the database connection

// Function to fetch data from the database
function fetchData($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Query failed: ' . $e->getMessage());
        return [];
    }
}

// Function to calculate grades based on subcomponent scores and weights
function calculateGrades($pdo) {
    $gradingsystem_id = 2;

    // Fetch component weights
    $weights_query = "SELECT weight1, weight2, weight3, weight4 FROM components_weights WHERE gradingsystem_id = :gradingsystem_id";
    $component_weights = fetchData($pdo, $weights_query, ['gradingsystem_id' => $gradingsystem_id]);

    if (!$component_weights) {
        error_log('No component weights found for gradingsystem_id: ' . $gradingsystem_id);
        return ['error' => 'Grading system not found.'];
    }

    $component_weights = $component_weights[0]; // Since fetchData returns an array of rows

    // Fetch all student scores
    $scores_query = "SELECT student_id, subcompscores1, subcompscores2, subcompscores3, subcompscores4, subcompscores5, subcompscores6, subcompscores7, subcompscores8, subcompscores9, subcompscores10, subcompscores11 FROM scores";
    $scores = fetchData($pdo, $scores_query);

    if (!$scores) {
        error_log('No scores found.');
    }

    $grades = [];
    foreach ($scores as $student_scores) {
        $total_score = 0;

        // Calculate component scores
        $component1_score = (($student_scores['subcompscores1'] / 60) + ($student_scores['subcompscores2'] / 60)) / 2;
        $total_score += $component_weights['weight1'] * $component1_score;

        $component2_score = (($student_scores['subcompscores3'] / 60) + ($student_scores['subcompscores4'] / 60) + ($student_scores['subcompscores5'] / 60)) / 3;
        $total_score += $component_weights['weight2'] * $component2_score;

        $component3_score = (($student_scores['subcompscores6'] / 60) + ($student_scores['subcompscores7'] / 60) + ($student_scores['subcompscores8'] / 60)) / 3;
        $total_score += $component_weights['weight3'] * $component3_score;

        $component4_score = (($student_scores['subcompscores9'] / 60) + ($student_scores['subcompscores10'] / 60) + ($student_scores['subcompscores11'] / 60)) / 3;
        $total_score += $component_weights['weight4'] * $component4_score;

        $grade = $total_score * 100;
        $grades[$student_scores['student_id']] = number_format($grade, 2);
    }

    return $grades;
}

// Call the function to calculate grades
$grades = calculateGrades($pdo);
?>
