<?php 
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);


// Connection to the database
$host = 'localhost';
$db = 'u415861906_infosec2236';
$user = 'u415861906_infosec2236';
$pass = 'r$Y9G>xxQQkOT6t|';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database connection failedd']);
    exit();
}

// Include data processing logic
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

$error_message = '';
// Handle login, signup, and insert row actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
 
    // Login function
    if ($action === 'login') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (!empty($username) && !empty($password)) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
                $stmt->execute(['username' => $username, 'password' => $password]);
                $user = $stmt->fetch();
                if ($user) {
                    echo json_encode(['success' => true, 'user' => $user]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Database query failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        }
    } elseif ($action === 'signup') {
        // Signup function
        $fname = isset($_POST['fname-signup']) ? $_POST['fname-signup'] : '';
        $lname = isset($_POST['lname-signup']) ? $_POST['lname-signup'] : '';
        $username_signup = isset($_POST['username-signup']) ? $_POST['username-signup'] : '';
        $email_signup = isset($_POST['email-signup']) ? $_POST['email-signup'] : '';
        $password_signup = isset($_POST['password-signup']) ? $_POST['password-signup'] : '';

        if (!empty($fname) && !empty($lname) && !empty($username_signup) && !empty($email_signup) && !empty($password_signup)) {
            try {
                // Check if username or email exists
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $stmt->execute(['username' => $username_signup, 'email' => $email_signup]);
                $existingUser = $stmt->fetch();

                if ($existingUser) {
                    echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
                } else {
                    // Insert new user
                    $stmt = $pdo->prepare("INSERT INTO users (user_fname, user_lname, username, email, password) VALUES (:fname, :lname, :username, :email, :password)");
                    $stmt->execute([
                        'fname' => $fname,
                        'lname' => $lname,
                        'username' => $username_signup,
                        'email' => $email_signup,
                        'password' => $password_signup
                    ]);
                    echo json_encode(['success' => true, 'message' => 'Account created successfully']);
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Database query failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
        }
    } elseif($action === 'addrow'){
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

    } elseif($action === 'editrow'){
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
    } elseif ($action === 'delrow') {
        $studentId = isset($_POST['id-no-del']) ? $_POST['id-no-del'] : null;

        if ($studentId) {
            try {
                // Prepare SQL statement to delete the student
                $sql = "DELETE FROM students WHERE student_id = :studentId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Row deleted successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Student ID is required']);
        }
    }
    exit();
}

// Fetch students data
$sql_students = "SELECT * FROM students";
try {
    $stmt = $pdo->query($sql_students); // Correct SQL variable
    if ($stmt) {
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $students = [];
    }
} catch (PDOException $e) {
    error_log('Query failed: ' . $e->getMessage());
    $students = []; // Fallback
}

$sql_gs = "SELECT * FROM gradingsystem";
try {
    $stmt = $pdo->query($sql_gs); // Correct SQL variable
    if ($stmt) {
        $gradingsystem = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $gradingsystem = [];
    }
} catch (PDOException $e) {
    error_log('Query failed: ' . $e->getMessage());
    $gradingsystem = []; // Fallback
}

$sql_c_w = "SELECT * FROM components_weights";
try {
    $stmt = $pdo->query($sql_c_w); // Correct SQL variable
    if ($stmt) {
        $components_weights = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $components_weights = [];
    }
} catch (PDOException $e) {
    error_log('Query failed: ' . $e->getMessage());
    $components_weights = []; // Fallback
}

$sql_subcomp = "SELECT * FROM subcomponents";
try {
    $stmt = $pdo->query($sql_subcomp); // Correct SQL variable
    if ($stmt) {
        $subcomponents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $subcomponents = [];
    }
} catch (PDOException $e) {
    error_log('Query failed: ' . $e->getMessage());
    $subcomponents = []; // Fallback
}

$sql_scores = "SELECT * FROM scores";
try {
    $stmt = $pdo->query($sql_scores); // Correct SQL variable
    if ($stmt) {
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $scores = [];
    }
} catch (PDOException $e) {
    error_log('Query failed: ' . $e->getMessage());
    $scores = []; // Fallback
}

// Define an array of student numbers (can also come from a database query)
if (!isset($pdo) || !$pdo instanceof PDO) {
    die("Database connection is not properly configured.");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Grading System</title>
    <style> 
            body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Poppins, sans-serif;
            background-color: #EAEEE5;
        }

        .sidepanel {
            background-color: #CADEC8;
            width: 15%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .circle-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            margin-top: 30px;
        }

        #profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            font-family: inherit;
            font-weight: bold;
        }

        #profile h2,
        #profile p {
            margin: 0;
            padding: 0;
            padding-bottom: 1%;
        }

        .sidebuttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.2em;
            margin-top: 40px;

        }

        #login-btn,
        #signup-btn {
            background-color: white;
            color: black;
            border-radius: 20px;
            width: 300%;
            padding: 8px;
            font-size: 1em;
            cursor: pointer;
            box-sizing: border-box;
            transition: transform 0.2s ease, box-shadow 0.5s ease;
        }


        #login-btn:hover,
        #signup-btn:hover {
            background-color: #5C8F6D;
            transform: scale(1.04);
        }

        /* popup form styling */
        .popup-form{
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 300px;
            max-height: 80vh; /* Set maximum height */
            overflow-y: auto; /* Enable vertical scrolling */
            text-align: center;

        }


        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: white;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px -7px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input:focus {
            outline: none;
            border-color: #5C8F6D;
        }

        button[type="submit"] {
            width: 100%;
            background-color: #494b49;
            border: none;
            padding: 10px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="button"] {
            margin-top: 2%;
            width: 100%;
            background-color: #494b49;
            border: none;
            padding: 10px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #5C8F6D;
            border-radius: 10px;
        }

        /* logout buttons >>>>> */


        .logout {
            margin-top: auto;
            background-color: #CADEC8;
            color: black;
            border: none;
            padding: 10px;
            width: 90%;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout:hover {
            background-color: #5C8F6D;
        }

        /* top panel section >>>>> */

        .toppanel {
            position: fixed;
            left: 15%;
            /* This starts the top panel from the right of the side panel */
            right: 0;
            /* Fills the rest of the width */
            padding: 0.5em;
            z-index: 10;
        }

        #sheet-title {
            margin-left: 0.5%;
        }

        #sheet-title input {
            font-family: inherit;
            margin-top: 0%;
            margin-bottom: 1.5%;
            padding: 10px;
            border: 3px solid #000;
            border-radius: 15px;
            background-color: #CADEC8;
            font-weight: 800;
            font-size: 30px;
            white-space: nowrap;
            width: auto;
        }

        #topbuttons {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .button-container button {
            background-color: transparent;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.5s ease;
        }

        .button-container button:hover {
            border-radius: 10px;
            transform: scale(1.1);
        }

        .button-container h2 {
            margin-top: 8px;
            font-size: 1em;
            color: #000;
        }

        #topbuttons img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            display: block;
        }



        /*tables*/
        .table-container {
            width: 85%;
            margin: 0 auto;
            margin-top: 14%;
            /* Create space for the top panel */
            overflow-x: auto;
            /* Allows horizontal scrolling if the table is too wide */
        }

        @media (max-width: 1620px),
        (max-width: 1280px),
        (max-width: 1510px) {
            .table-container {
                width: 85%;
                margin: 0 auto;
                margin-top: 18%;
                overflow-x: auto;
            }

            .sidebutton {
                font-size: 0.7em;
            }

            #profile {
                font-size: 0.8em;

            }

            #login-btn,
            #signup-btn {
                background-color: white;
                /* Button background color */
                color: black;
                /* Text color */
                border-radius: 20px;
                width: 270%;
                padding: 8px;
                /* Reduced padding for better fit */
                font-size: 0.8em;
                /* Adjust font size for readability */
                cursor: pointer;
                /* Change the cursor to indicate a clickable element */
                box-sizing: border-box;
                /* Ensures padding and borders are included in width calculation */
                transition: transform 0.2s ease, box-shadow 0.5s ease;
            }
        }


        #editable-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        #subject-name,
        #term,
        #section,
        #ay {
            width: 30%;
        }

        #subject-name-td, #term-td{
            background-color: #CADEC8;
        }

        #editable-table th,
        #editable-table td {
            border: 2px solid black;
            padding: 8px;
            text-align: center;
        }

        #editable-table th,
        #subject-name,
        #term,
        #section,
        #acad_year {
            background-color: #CADEC8;
            color: black;
            font-weight: bold;
            text-align: center;
        }

        /* Alternating Row Colors */
        #editable-table tr:nth-child(odd) {
            background-color: #7ABD87;
            /* Color for odd rows */
        }

        #editable-table tr:nth-child(even) {
            background-color: #CADEC8;
            /* Color for even rows */
        }

        [contenteditable="true"] {
            background-color: #f9f9f9;
            outline: none;
        }

        [contenteditable="true"]:focus {
            border: 1px solid #007bff;
            background-color: white;
        }

        input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .table-container button {
            padding: 10px 15px;
            background-color: #7ABD87;
            color: black;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid black;
        }

        button:hover {
            background-color: #5C8F6D;
        }

        th {
            text-align: center;
        }

        .table-container input{
            background-color:transparent;
            border:none;
        }

        #weight1, #component1, #weight2, #component2, #weight3, #component3, #weight4, #component4,
        #subcomponent1, #subcomponent2, #subcomponent3, #subcomponent4, #subcomponent5, #subcomponent6,
        #subcomponent7, #subcomponent8, #subcomponent9, #subcomponent10, #subcomponent11 {
            text-align: center;
            margin: 0; /* Remove any margin */
            padding: 2px 0; /* Reduce padding */
            font-weight: bold; /* Make text bold */
        }

        #component1, #component2, #component3, #component4{
            font-size: large;
        }


    </style>
</head>
<body>

    <!-- Login Popup -->
    <div id="popupForm" class="popup-form">
        <div class="form-container">
            <h2>Login</h2>

            <form id="loginForm" method="POST" action="index.php">
                <input type="hidden" name="action" value="login">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />

                <button type="submit">Submit</button>
                <button type="button" id="showSignUp">Sign Up</button>
            </form>
        </div>
    </div>

    <!-- Sign Up Popup -->
    <div id="popupForm1" class="popup-form">
        <div class="form-container">
            <h2>Sign Up</h2>

            <form id="signupForm" method="POST" action="index.php">
                <input type="hidden" name="action" value="signup">
                <label for="fname-signup">First Name:</label>
                <input type="text" id="fname-signup" name="fname-signup" required/>

                <label for="lname-signup">Last Name:</label>
                <input type="text" id="lname-signup" name="lname-signup" required/>

                <label for="username-signup">Username:</label>
                <input type="text" id="username-signup" name="username-signup" required/>

                <label for="email-signup">Email:</label>
                <input type="email" id="email-signup" name="email-signup" required/>

                <label for="password-signup">Password:</label>
                <input type="password" id="password-signup" name="password-signup" required/>

                <button type="submit">Submit</button>
                <button type="button" id="showLogin">Back to Login</button>
            </form>
        </div>
    </div>
    <!-- Add Row Popup -->
    <div id="popupFormAddRow" class="popup-form">
        <div class="form-container">
            <h2>Add New Row</h2>

            <form id="addRowForm" method="POST" action="index.php">
                <input type="hidden" name="action" value="addrow">
                <label for="new-id-no">Id No:</label>
                <input type="text" id="new-id-no" name="new-id-no" required />

                <label for="new-student-no">Student No:</label>
                <input type="text" id="new-student-no" name="new-student-no" required />

                <label for="new-fullname">Full Name:</label>
                <input type="text" id="new-fullname" name="new-fullname" required />

                <label for="new-course">Course:</label>
                <input type="text" id="new-course" name="new-course" required />

                <?php for ($i = 1; $i <= 11; $i++): ?>
                <label for="new-subcomponent<?= $i ?>">Subcomponent <?= $i ?>:</label>
                <input type="text" id="new-subcomponent<?= $i ?>" name="new-subcomponent<?= $i ?>" required />
                <?php endfor; ?>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

        <!-- Edit Row Popup -->
    <div id="popupFormEditRow" class="popup-form">
        <div class="form-container">
            <h2>Edit Row</h2>

            <form id="editRowForm" method="POST" action="index.php">
                <input type="hidden" name="action" value="editrow">
                <label for="edit-id-no">Id No:</label>
                <input type="text" id="edit-id-no" name="edit-id-no" required />

                <label for="edit-student-no">Student No:</label>
                <input type="text" id="edit-student-no" name="edit-student-no" required />

                <label for="edit-fullname">Full Name:</label>
                <input type="text" id="edit-fullname" name="edit-fullname" required />

                <label for="edit-course">Course:</label>
                <input type="text" id="edit-course" name="edit-course" required />

                <?php for ($i = 1; $i <= 11; $i++): ?>
                <label for="edit-subcomponent<?= $i ?>">Subcomponent <?= $i ?>:</label>
                <input type="text" id="edit-subcomponent<?= $i ?>" name="edit-subcomponent<?= $i ?>" required />
                <?php endfor; ?>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- Del Row Popup -->
    <div id="popupFormDelRow" class="popup-form">
        <div class="form-container">
            <h2>Delete Row</h2>

            <form id="delRowForm" method="POST" action="index.php">
                <input type="hidden" name="action" value="delrow">
                <label for="id-no-del">Id No:</label>
                <input type="text" id="id-no-del" name="id-no-del" required />

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

   

    <!-- Side Panel -->
    <section class="sidepanel">
        <div id="profile">
            <img src="rooster.jpg" alt="rooster" class="circle-image" id="profile-img">
            <h2 id="profile-name"></h2>
            <p id="profile-email"></p>
        </div>

        <div class="sidebuttons">
            <button id="login-btn"> Login</button>
            <button id="signup-btn"> Sign up</button>
        </div>
    </section>

    <!-- Top Panel with Buttons -->
    <section class="toppanel">
        <div id="sheet-title">
            <input type="text" placeholder="Untitled Sheet">
        </div>

        <div id="topbuttons">
            <div class="button-container">
                <button id="addBtn"> <img src="section.png" alt="addrow"> </button> 
                <h2>Add Row</h2>
            </div>
            <div class="button-container">
                <button id="editBtn"><img src="table.png" alt="edit"></button>
                <h2>Edit</h2>
            </div>
            <div class="button-container">
                <button id="deleteBtn"><img src="delete.png" alt="delete"></button>
                <h2>Delete</h2>
            </div>
        </div>
    </section>

    <!-- Table Form -->
    <div class="table-container">
        <form method="POST" id="grades-form">
            <table id="editable-table">
                <thead>
                    <?php foreach ($gradingsystem as $gs): ?>
                        <tr>
                            <th colspan="3">SUBJECT NAME:</th>
                            <td colspan="6" id="subject-name-td"><input type="text" id="subject-name" name="subject-name[]" value="<?= $gs['subject_name']; ?>"></td>
                            <th colspan="2">TERM:</th>
                            <td colspan="12" id="term-td"><input type="text" id="term" name="term[]" value="<?= $gs['term']; ?>"></td>
                        </tr>
                        <tr>
                            <th colspan="3">SECTION:</th>
                            <td colspan="6"><input type="text" id="section" name="section[]" value="<?= $gs['section']; ?>"></td>
                            <th colspan="2">A.Y.:</th>
                            <td colspan="12"><input type="text" id="acad_year" name="acad_year[]" value="<?= $gs['acad_year']; ?>"></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>No.</th>
                            <th colspan="2">Student No.</th>
                            <th colspan="6">Full Name</th>
                            <th colspan="2">Course</th>
                            <?php foreach ($components_weights as $c_w): ?>
                            <th  colspan="2" class="merged">
                                <div><input type="text" id="weight1" name="weight1[]" placeholder="%" value="<?= $c_w['weight1']; ?>"></div>
                                <div><input type="text" id="component1" name="component1[]" value="<?= $c_w['component1']; ?>" placeholder="Component 1"></div>
                            </th>
                            <th colspan="3" class="merged">
                                <div><input type="text" id="weight2" name="weight2[]" value="<?= $c_w['weight2']; ?>" placeholder="%"></div>
                                <div><input type="text" id="component2" name="component2[]" value="<?= $c_w['component2']; ?>" placeholder="Component 2"></div>
                            </th>
                            <th colspan="3" class="merged">
                                <div><input type="text" id="weight3" name="weight3[]" value="<?= $c_w['weight3']; ?>"placeholder="%"></div>
                                <div><input type="text" id="component3" name="component3[]" value="<?= $c_w['component3']; ?>" placeholder="Component 3"></div>
                            </th>
                            <th colspan="3" class="merged">
                                <div><input type="text" id="weight4"  name="weight4[]" value="<?= $c_w['weight4']; ?>" placeholder="%"></div>
                                <div><input type="text" id="component4" name="component4[]" value="<?= $c_w['component4']; ?>" placeholder="Component 4"></div>
                            </th>
                            <?php endforeach; ?>
                            <th>Grade</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th colspan="2"></th>
                            <th colspan="6"></th>
                            <th colspan="2"></th>
                            <?php foreach ($subcomponents as $subcomp): ?>
                            <th><input type="text" id="subcomponent1" name="subcomponent1[]" value="<?= $subcomp['subcomponent1']; ?>" ></th>
                            <th><input type="text" id="subcomponent2" name="subcomponent2[]" value="<?= $subcomp['subcomponent2']; ?>"></th>
                            <th><input type="text" id="subcomponent3" name="subcomponent3[]" value="<?= $subcomp['subcomponent3']; ?>"></th>
                            <th><input type="text" id="subcomponent4" name="subcomponent4[]" value="<?= $subcomp['subcomponent4']; ?>"></th>
                            <th><input type="text" id="subcomponent5" name="subcomponent5[]" value="<?= $subcomp['subcomponent5']; ?>"></th>
                            <th><input type="text" id="subcomponent6"  name="subcomponent6[]" value="<?= $subcomp['subcomponent6']; ?>"></th>
                            <th><input type="text" id="subcomponent7" name="subcomponent7[]" value="<?= $subcomp['subcomponent7']; ?>"></th>
                            <th><input type="text" id="subcomponent8" name="subcomponent8[]" value="<?= $subcomp['subcomponent8']; ?>"></th>
                            <th><input type="text" id="subcomponent9" name="subcomponent9[]" value="<?= $subcomp['subcomponent9']; ?>"></th>
                            <th><input type="text" id="subcomponent10" name="subcomponent10[]" value="<?= $subcomp['subcomponent10']; ?>"></th>
                            <th><input type="text" id="subcomponent11" name="subcomponent11[]" value="<?= $subcomp['subcomponent11']; ?>"></th>
                            <th></th>
                            <?php endforeach; ?>
                        </tr>
                </thead>
                <tbody>
                <tr>
                <?php 
                // Assuming $students and $scores arrays are indexed in such a way that you can access them together.
                $scoresByStudentNum = [];
                foreach ($scores as $score) {
                    $scoresByStudentNum[$score['student_id']] = $score;
                }

                ?>

                <?php foreach ($students as $i => $student): ?>
                    <?php
                    // Look for the student's score based on student_num
                    $studentScore = isset($scoresByStudentNum[$student['student_id']]) 
                        ? $scoresByStudentNum[$student['student_id']] 
                        : null; // Default to null if no score is found
                        
                ?>
                <tr>
                    <td><input type="text" id="no" value="<?= htmlspecialchars($student['student_id']); ?>"readonly></td>
                    <td colspan="2"><input type="text" id="student-no" name="student-no" value="<?= htmlspecialchars($student['student_num']); ?>"></td>
                    <td colspan="6"><input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($student['fullname']); ?>"></td>
                    <td colspan="2"><input type="text" id="course" name="course" value="<?= htmlspecialchars($student['course']); ?>"></td>
                    <!-- Scores -->
                    <?php if ($studentScore): ?>
                        <td><input type="text" id="subcompscores1" name="subcompscores1" value="<?= $studentScore['subcompscores1']; ?>"></td>
                        <td><input type="text" id="subcompscores2" name="subcompscores2" value="<?= $studentScore['subcompscores2']; ?>"></td>
                        <td><input type="text" id="subcompscores3" name="subcompscores3" value="<?= $studentScore['subcompscores3']; ?>"></td>
                        <td><input type="text" id="subcompscores4" name="subcompscores4" value="<?= $studentScore['subcompscores4']; ?>"></td>
                        <td><input type="text" id="subcompscores5" name="subcompscores5" value="<?= $studentScore['subcompscores5']; ?>"></td>
                        <td><input type="text" id="subcompscores6" name="subcompscores6" value="<?= $studentScore['subcompscores6']; ?>"></td>
                        <td><input type="text" id="subcompscores7" name="subcompscores7" value="<?= $studentScore['subcompscores7']; ?>"></td>
                        <td><input type="text" id="subcompscores8" name="subcompscores8" value="<?= $studentScore['subcompscores8']; ?>"></td>
                        <td><input type="text" id="subcompscores9" name="subcompscores9" value="<?= $studentScore['subcompscores9']; ?>"></td>
                        <td><input type="text" id="subcompscores10" name="subcompscores10" value="<?= $studentScore['subcompscores10']; ?>"></td>
                        <td><input type="text" id="subcompscores11" name="subcompscores11" value="<?= $studentScore['subcompscores11']; ?>"></td>
                        <td>
                             <!-- Display grades directly in the column -->
                            <?php if (isset($grades[$studentScore['student_id']])): ?>
                                <?= htmlspecialchars($grades[$studentScore['student_id']]); ?>
                            <?php else: ?>
                                No grade available
                            <?php endif; ?>
                        </td>
                    <?php else: ?>
                        <!-- If no score exists, display blank fields -->
                        <td><input type="text" id="subcompscores1" name="subcompscores1" value=""></td>
                        <td><input type="text" id="subcompscores2" name="subcompscores2" value=""></td>
                        <td><input type="text" id="subcompscores3" name="subcompscores3" value=""></td>
                        <td><input type="text" id="subcompscores4" name="subcompscores4" value=""></td>
                        <td><input type="text" id="subcompscores5" name="subcompscores5" value=""></td>
                        <td><input type="text" id="subcompscores6" name="subcompscores6" value=""></td>
                        <td><input type="text" id="subcompscores7" name="subcompscores7" value=""></td>
                        <td><input type="text" id="subcompscores8" name="subcompscores8" value=""></td>
                        <td><input type="text" id="subcompscores9" name="subcompscores9" value=""></td>
                        <td><input type="text" id="subcompscores10" name="subcompscores10" value=""></td>
                        <td><input type="text" id="subcompscores11" name="subcompscores11" value=""></td>
                        <td>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tr>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');
        const login = document.getElementById('login-btn');
        const signup = document.getElementById('signup-btn');
        const popupForm = document.getElementById('popupForm');
        const popupForm1 = document.getElementById('popupForm1');
        const profileName = document.getElementById('profile-name');
        const profileEmail = document.getElementById('profile-email');
        const LOGIN_EXPIRY_KEY = 'loginExpiry';

        console.log("DOM fully loaded and parsed");

        // loginform
        login.addEventListener('click', () => {
            popupForm.style.display = 'flex';
        });

        // signupform
        signup.addEventListener('click', () => {
            popupForm1.style.display = 'flex';
        });

        // alternate between forms
        document.getElementById("showSignUp").addEventListener("click", () => {
            popupForm.style.display = "none";
            popupForm1.style.display = "flex";
        });

        document.getElementById("showLogin").addEventListener("click", () => {
            popupForm1.style.display = "none";
            popupForm.style.display = "flex";
        });


        // Function to check login state on page load
        const checkLoginState = () => {
            const loginExpiry = localStorage.getItem(LOGIN_EXPIRY_KEY);
            const currentTime = Date.now();

            if (loginExpiry && currentTime < parseInt(loginExpiry)) {
                console.log('User session still active.');
                popupForm.style.display = 'none';

                // Restore user data from localStorage
                const fullName = localStorage.getItem('userFullName');
                const email = localStorage.getItem('userEmail');

                if (fullName && email) {
                    profileName.textContent = fullName;
                    profileEmail.textContent = email;
                } else {
                    console.warn("User data not found in localStorage");
                }
            } else {
                console.log('User session expired or not logged in.');
                localStorage.clear(); // Clear any stale data
                popupForm.style.display = 'flex';
            }
        };

        checkLoginState(); // Call on page load

        // Handle login form submission
        loginForm?.addEventListener('submit', function (event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const data = new FormData();
            data.append('action', 'login');
            data.append('username', username);
            data.append('password', password);

            fetch('index.php', {
                method: 'POST',
                body: data
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Login successful!');

                        // Save login state in localStorage
                        const expiryTime = Date.now() + 10 * 60 * 1000; // 10 minutes
                        localStorage.setItem(LOGIN_EXPIRY_KEY, expiryTime.toString());
                        localStorage.setItem('userFullName', `${data.user.user_fname} ${data.user.user_lname}`);
                        localStorage.setItem('userEmail', data.user.email);

                        // Update UI
                        profileName.textContent = `${data.user.user_fname} ${data.user.user_lname}`;
                        profileEmail.textContent = data.user.email;
                        popupForm.style.display = 'none';
                        popupForm1.style.display = 'none';

                        window.addEventListener('click', (event) => {
                        if (event.target === popupForm || event.target === popupForm1) {
                            popupForm.style.display = 'none';
                            popupForm1.style.display = 'none';
                        }
                    });
                    } else {
                        alert('Invalid username or password.');
                    }
                })
                .catch(error => {
                    console.error('Error during login:', error);
                });
        });

        if (signupForm) {
        signupForm.addEventListener("submit", function (event) {
            event.preventDefault();
            // prevent page reload
            const fname = document.getElementById("fname-signup").value;
            const lname = document.getElementById("lname-signup").value;
            const username = document.getElementById("username-signup").value;
            const email = document.getElementById("email-signup").value;
            const password = document.getElementById("password-signup").value;
            const data = new FormData();

            data.append('action', 'signup');
            data.append("fname-signup", fname);
            data.append("lname-signup", lname);
            data.append("username-signup", username);
            data.append("email-signup", email);
            data.append("password-signup", password);
            // debug: log the FormData to verify contents
            for (let pair of data.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }
            // AJAX request to login.php to register the new account
            fetch("index.php", { method: "POST", body: data })
                .then((response) => response.json())
                // Parse the JSON response from PHP
                .then((data) => {
                    if (data.success) {
                        popupForm1.style.display = "none";
                        console.log("Account created successfully!");
                        alert("Account created successfully!");

                        console.log(fname, lname, username, email, password);

                        console.log("Profile Name Element:", profileName);
                        console.log("Profile Email Element:", profileEmail);

                        profileName.textContent = fname + ' ' + lname;
                        profileEmail.textContent = email;
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error during signup:", error);
                });
        });
    }});
    
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const popupFormAddRow = document.getElementById('popupFormAddRow');
        const addRowForm = document.getElementById('addRowForm');
        const addRow = document.getElementById('addBtn'); // Ensure this element exists in your HTML

        console.log("successful addDOM");

        addRow.addEventListener('click', () => {
            popupFormAddRow.style.display = 'flex';
        });

        window.addEventListener('click', (event) => {
            if (event.target === popupFormAddRow) {
                popupFormAddRow.style.display = 'none';
            }
        });

        addRowForm.addEventListener('submit', function (event) {
            event.preventDefault();  // prevent page reload
        
            const studentId = document.getElementById('new-id-no').value;
            const studentNum = document.getElementById('new-student-no').value;
            const studentName = document.getElementById('new-fullname').value;
            const course = document.getElementById('new-course').value;

            const data = new FormData();
            data.append('action', 'addrow');
            data.append('new-id-no', studentId);
            data.append('new-student-no', studentNum);
            data.append('new-fullname', studentName);
            data.append('new-course', course);

            for (let i = 1; i <= 11; i++) {
                const subcomponent = document.getElementById(`new-subcomponent${i}`).value;
                data.append(`new-subcomponent${i}`, subcomponent);
            }

            fetch('index.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json()) // parse json response from php
            .then(data => {
                if (data.success) {
                    console.log('Inserted successfully!');
                    popupFormAddRow.style.display = 'none';
                    window.location.reload();
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        // Delete Row
    const deleteBtn = document.getElementById('deleteBtn');
    const popupFormDeleteRow = document.getElementById('popupFormDelRow');
    const deleteForm = document.getElementById('delRowForm');

    deleteBtn.addEventListener('click', () => {
        popupFormDeleteRow.style.display = 'flex';
    });

    window.addEventListener('click', (event) => {
        if (event.target === popupFormDeleteRow) {
            popupFormDeleteRow.style.display = 'none';
        }
    });

    deleteForm.addEventListener('submit', function (event) {
        event.preventDefault(); 

        const studentId = document.getElementById('id-no-del').value;

        const data = new FormData();
        data.append('action', 'delrow');
        data.append('id-no-del', studentId);

        fetch('index.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json()) // parse json response from php
        .then(data => {
            if (data.success) {
                console.log('Deleted successfully!');
                popupFormDeleteRow.style.display = 'none';
                window.location.reload();
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const popupFormEditRow = document.getElementById('popupFormEditRow');
        const editRowForm = document.getElementById('editRowForm');
        const editBtns = document.getElementById('editBtn'); // Ensure this element exists in your HTML

        console.log("successful editDOM");

        editBtns.addEventListener('click', () => {
            popupFormEditRow.style.display = 'flex';
        });
        
        window.addEventListener('click', (event) => {
            if (event.target === popupFormEditRow) {
                popupFormEditRow.style.display = 'none';
            }
        });

        editRowForm.addEventListener('submit', function (event) {
            event.preventDefault();  // prevent page reload
        
            const studentId = document.getElementById('edit-id-no').value;
            const studentNum = document.getElementById('edit-student-no').value;
            const studentName = document.getElementById('edit-fullname').value;
            const course = document.getElementById('edit-course').value;

            const data = new FormData();
            data.append('action', 'editrow');
            data.append('edit-id-no', studentId);
            data.append('edit-student-no', studentNum);
            data.append('edit-fullname', studentName);
            data.append('edit-course', course);

            for (let i = 1; i <= 11; i++) {
                const subcomponent = document.getElementById(`edit-subcomponent${i}`).value;
                data.append(`edit-subcomponent${i}`, subcomponent);
            }

            fetch('index.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json()) // parse json response from php
            .then(data => {
                if (data.success) {
                    console.log('Edited successfully!');
                    popupFormEditRow.style.display = 'none';
                    window.location.reload();
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    </script>
</body>
</html>
