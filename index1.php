<?php include 'conn.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Grading System</title>
    <link rel="stylesheet" href="index1.css">
</head>

<body>

    <div id="popupForm" class="popup-form">
        <div class="form-container">
           <h2>Login</h2>

        <form id="loginForm" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required />
 
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Submit</button>
            <button type="button" id="showSignUp">Sign Up</button>
        </form>

        </div>
    </div>


    <div id="popupForm1" class="popup-form">
    <div class="form-container">
        <h2>Sign Up</h2>

        <form id="signupForm" method="POST">
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

    
    <section class="toppanel">
            <div id="sheet-title">
                <input type="text" placeholder="Untitled Sheet">
            </div>
    
            <div id="topbuttons">
                <div class="button-container">
                    <button><img src="imgs/section.png" alt="section"></button>
                    <h2>Add Row</h2>
                </div>
                <div class="button-container">
                    <button><img src="imgs/table.png" alt="table"></button>
                    <h2>Edit</h2>
                </div>
                <div class="button-container">
                    <button><img src="imgs/lock.png" alt="lock"></button>
                    <h2>Save (Update)</h2>
                </div>
                <div class="button-container">
                    <button><img src="imgs/delete.png" alt="delete"></button>
                    <h2>Delete</h2>
                </div>
            </div>
            
    </section>

    <div class="table-container">
        <table id="editable-table">
            <thead>
                <tr>
                    <th colspan="2">SUBJECT NAME:</th>
                    <td colspan="4" contenteditable="true" id="subject-name"></td>
                    <th colspan="3">TERM:</th>
                    <td colspan="8" contenteditable="true" id="term"></td>
                </tr>
                <tr>
                    <th colspan="2">SECTION:</th>
                    <td colspan="4" contenteditable="true" id="section"></td>
                    <th colspan="3">A.Y.:</th>
                    <td colspan="8" contenteditable="true" id="ay"></td>
                </tr>
                <tr>
                    <th>No.</th>
                    <th>Student No.</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th  colspan="2" class="merged">
                        <div contenteditable="true">20%</div>
                        <div contenteditable="true">Exams</div>
                    </th>
                    <th colspan="3" class="merged">
                        <div contenteditable="true">20%</div>
                        <div contenteditable="true">Project</div>
                    </th>
                    <th colspan="3" class="merged">
                        <div contenteditable="true">50%</div>
                        <div contenteditable="true">Exercises</div>
                    </th>
                    <th colspan="3" class="merged">
                        <div contenteditable="true">10%</div>
                        <div contenteditable="true">Class Participation</div>
                    </th>
                    <th>Grade</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th contenteditable="true">Midterms</th>
                    <th contenteditable="true">Finals</th>
                    <th contenteditable="true">Proj 1</th>
                    <th contenteditable="true">Proj 2</th>
                    <th contenteditable="true">Proj 3</th>
                    <th contenteditable="true">Exercise 1</th>
                    <th contenteditable="true">Exercise 2</th>
                    <th contenteditable="true">Exercise 3</th>
                    <th contenteditable="true">Attendance</th>
                    <th contenteditable="true">Recitation</th>
                    <th contenteditable="true">Plus Points</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td contenteditable="true">1</td>
                    <td contenteditable="true">2022-101538</td>
                    <td contenteditable="true">Navarro, Jascent Pearl G.</td>
                    <td contenteditable="true">BSCS-ML</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">30</td>
                    <td contenteditable="true">10</td>
                    <td contenteditable="true">20</td>
                    <td contenteditable="true">15</td>
                    <td contenteditable="true">95</td>
                    <td contenteditable="true">98</td>
                    <td contenteditable="true">96</td>
                    <td contenteditable="true">100</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">100</td>
                    <td></td>
                </tr>
                <tr>
                    <td contenteditable="true">2</td>
                    <td contenteditable="true">2022-104741</td>
                    <td contenteditable="true">Danga, Diana Nicole D.</td>
                    <td contenteditable="true">BSCS-ML</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">30</td>
                    <td contenteditable="true">10</td>
                    <td contenteditable="true">20</td>
                    <td contenteditable="true">15</td>
                    <td contenteditable="true">95</td>
                    <td contenteditable="true">98</td>
                    <td contenteditable="true">96</td>
                    <td contenteditable="true">100</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">100</td>
                    <td></td>
                </tr>
                <tr>
                    <td contenteditable="true">3</td>
                    <td contenteditable="true">2021-100202</td>
                    <td contenteditable="true">Gannaban, John Efren V.</td>
                    <td contenteditable="true">BSCS-ML</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">30</td>
                    <td contenteditable="true">10</td>
                    <td contenteditable="true">20</td>
                    <td contenteditable="true">15</td>
                    <td contenteditable="true">95</td>
                    <td contenteditable="true">98</td>
                    <td contenteditable="true">96</td>
                    <td contenteditable="true">100</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">100</td>
                    <td></td>
                </tr>
                <tr>
                    <td contenteditable="true">4</td>
                    <td contenteditable="true">2022-104741</td>
                    <td contenteditable="true">Siaton, Allen M.</td>
                    <td contenteditable="true">BSCS-ML</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">30</td>
                    <td contenteditable="true">10</td>
                    <td contenteditable="true">20</td>
                    <td contenteditable="true">15</td>
                    <td contenteditable="true">95</td>
                    <td contenteditable="true">98</td>
                    <td contenteditable="true">96</td>
                    <td contenteditable="true">100</td>
                    <td contenteditable="true">50</td>
                    <td contenteditable="true">100</td>
                    <td></td>
                </tr>
                <tr>
                    <td contenteditable="true">5</td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="index1.js"></script>
</body>
</html>