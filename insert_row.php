<?php
    include 'conn.php'; // Database connection

    // Logic file of the add row features
        try {
            
            // this code is checking the form for specific pieces of information: If the information is there, it saves it; 
            // if not, it uses an empty value (null) instead. This helps the program avoid errors when something is missing.
            $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;
            $subcomp_id = isset($_POST['subcomp_id']) ? $_POST['subcomp_id'] : null;
            $student_num = isset($_POST['student_num']) && !empty($_POST['student_num']) ? $_POST['student_num'] : null;
            $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : ''; 
            $course = isset($_POST['course']) ? $_POST['course'] : ''; 
            
            // Default scores
            $subcompscores1 = ''; 
            $subcompscores2 = '';
            $subcompscores3 = '';
            $subcompscores4 = '';
            $subcompscores5 = '';
            $subcompscores6 = '';
            $subcompscores7 = '';
            $subcompscores8 = '';
            $subcompscores9 = '';
            $subcompscores10 = '';
            $subcompscores11 = '';
            $grade = '';

            // Fetch a valid subcomp_id from the subcomponents table (if applicable)
            if ($subcomp_id === null) {
                $stmt = $pdo->prepare("SELECT subcomp_id FROM subcomponents WHERE subcomp_id = :subcomp_id_name");
                $stmt->execute(['subcomp_id_name' => '9']); 
                if ($stmt->rowCount() > 0) {
                    $subcomp_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $subcomp_id = $subcomp_data['subcomp_id']; // Retrieve subcomp_id from the query result
                } else {
                    echo "subcomp_id does not exist in the subcomponents table.";
                    exit;
                }
            }

            // Check if the student_num is unique (if it's not NULL)
            if ($student_num !== null) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_num = :student_num");
                $stmt->execute(['student_num' => $student_num]);
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    echo "The student number already exists. Please provide a unique student number.";
                    exit; // Stop further processing if student_num is not unique
                }
            }

            // SQL query to insert a new row (for students)
            $stmt = $pdo->prepare("
                INSERT INTO students (student_num, fullname, course) 
                VALUES (:student_num, :fullname, :course)
            ");

            // Execute the query with bound parameters for students
            $stmt->execute([
                'student_num' => $student_num, // This can now be NULL if the user hasn't input it yet
                'fullname' => $fullname,
                'course' => $course,
            ]);

            // this code is for the purpose of when you insert a new record into a database table that has an auto-incremented primary key 
            // (such as student_id), the database automatically generates a unique ID for that row. 
            // This ID is not something you provide—it's generated by the database.
            $student_id = $pdo->lastInsertId();

            // SQL query to insert a new row (for scores)
            $stmt = $pdo->prepare("
                INSERT INTO scores (student_id, subcomp_id, subcompscores1, subcompscores2, subcompscores3, subcompscores4, subcompscores5, subcompscores6, subcompscores7, subcompscores8, subcompscores9, subcompscores10, subcompscores11, grade) 
                VALUES (:student_id, :subcomp_id, :subcompscores1, :subcompscores2, :subcompscores3, :subcompscores4, :subcompscores5, :subcompscores6, :subcompscores7, :subcompscores8, :subcompscores9, :subcompscores10, :subcompscores11, :grade)
            ");

            // Execute the query with bound parameters for scores
            $stmt->execute([
                'student_id' => $student_id,  // Use the student_id obtained from the previous insert
                'subcomp_id' => $subcomp_id,
                'subcompscores1' => $subcompscores1,
                'subcompscores2' => $subcompscores2,
                'subcompscores3' => $subcompscores3,
                'subcompscores4' => $subcompscores4,
                'subcompscores5' => $subcompscores5,
                'subcompscores6' => $subcompscores6,
                'subcompscores7' => $subcompscores7,
                'subcompscores8' => $subcompscores8,
                'subcompscores9' => $subcompscores9,
                'subcompscores10' => $subcompscores10,
                'subcompscores11' => $subcompscores11,
                'grade' => $grade
            ]);

            header('Location: index1.php'); // This code prevents redirecting from another webpage.
            exit;
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Insert error: " . $e->getMessage());
            // Show detailed error message to help with troubleshooting (only for development)
            echo "Insert error: " . $e->getMessage();
        }
?>