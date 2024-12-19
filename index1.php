<?php 

include 'conn.php'; 
include 'data_processing.php';

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

$i = 1; // Initialize the variable


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
    <link rel="stylesheet" href="index1.css">
</head>
<body>

    <!-- Login Popup -->
    <div id="popupForm" class="popup-form">
        <div class="form-container">
            <h2>Login</h2>

            <form id="loginForm" method="POST" action="login.php">
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

            <form id="signupForm" method="POST" action="signup.php">
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

    <!-- Side Panel -->
    <section class="sidepanel">
        <div id="profile">
            <img src="imgs/rooster.jpg" alt="rooster" class="circle-image" id="profile-img">
            <h2 id="profile-name">Fred Rooster</h2>
            <p id="profile-email">friedfredchicken@gmail.com</p>
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
                <button> <a href="insert_row.php"><img src="imgs/section.png" alt="section"></a> </button> <!-- Add row feature -->
                <h2>Add Row</h2>
            </div>
            <div class="button-container">
                <button id="editBtn"><img src="imgs/table.png" alt="table"></button>
                <h2>Edit</h2>
            </div>
            <div class="button-container">
                <button id="saveBtn"><img src="imgs/lock.png" alt="lock"></button>
                <h2>Save (Update)</h2>
            </div>
            <div class="button-container">
                <button id="deleteBtn"><img src="imgs/delete.png" alt="delete"></button>
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
                            <td colspan="6"><input type="text" id="subject-name" name="subject-name[]" value="<?= $gs['subject_name']; ?>"></td>
                            <th colspan="2">TERM:</th>
                            <td colspan="12"><input type="text" id="term" name="term[]" value="<?= $gs['term']; ?>"></td>
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
                    <td><input type="text" id="no" name="no" value="<?= $i++; ?>"></td>
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
    <script src="index1.js"></script>
</body>
</html>
