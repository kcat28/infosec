<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

include 'conn.php'; // database conn

if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// determine if the request is for login or signup
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'login') {
    // Login function
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
    }
}
 elseif ($action === 'signup') {
    // signup function
    $fname = isset($_POST['fname-signup']) ? $_POST['fname-signup'] : '';
    $lname = isset($_POST['lname-signup']) ? $_POST['lname-signup'] : '';
    $username_signup = isset($_POST['username-signup']) ? $_POST['username-signup'] : '';
    $email_signup = isset($_POST['email-signup']) ? $_POST['email-signup'] : '';
    $password_signup = isset($_POST['password-signup']) ? $_POST['password-signup'] : '';

    if (!empty($fname) && !empty($lname) && !empty($username_signup) && !empty($email_signup) && !empty($password_signup)) {
        try {
            // check if username or email exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username_signup, 'email' => $email_signup]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
            } else {
                // insert new user
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
}
?>
