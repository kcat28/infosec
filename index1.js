document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById('loginForm'); 
    const login = document.getElementById('login-btn');
    const signup = document.getElementById('signup-btn');
    const popupForm = document.getElementById('popupForm');
    const popupForm1 = document.getElementById('popupForm1');
    let isLoggedIn = false;
    console.log("Successful DOM loaded");

    // loginform
    login.addEventListener('click', () => {
        popupForm.style.display = 'flex';
    });

    // signupform
    signup.addEventListener('click', () => {
        popupForm1.style.display = 'flex';
    });

    // alternate bet forms
    document.getElementById("showSignUp").addEventListener("click", () => {
        popupForm.style.display = "none"; 
        popupForm1.style.display = "flex"; 
    });

    document.getElementById("showLogin").addEventListener("click", () => {
        popupForm1.style.display = "none";
        popupForm.style.display = "flex"; 
    });

    // display login upon laod
    popupForm.style.display = 'flex'; 

    // listen form submission
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent page reload

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        const data = new FormData();
        data.append('username', username);
        data.append('password', password);

        // debug logging form to validate data
        for (let pair of data.entries()) {
            console.log(pair[0] + ": " + pair[1]);
        }

        // AJAX request to login.php to validate the login inputted
        fetch('/infosec/login.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json()) // parse json response from php
        .then(data => {
            if (data.success) {
                popupForm.style.display = 'none'; 
                console.log('Login successful!');
                isLoggedIn = true;
                window.addEventListener('click', (event) => {
                    if (event.target === popupForm || event.target === popupForm1) {
                        popupForm.style.display = 'none';
                        popupForm1.style.display = 'none';
                    }
                });
            } else {
                alert('Invalid username or password!');
            }
        })
        .catch(error => {
            console.error('Error during login:', error);
        });
    });

    signupForm.addEventListener('submit', function(event) { event.preventDefault(); // Prevent page reload 
    const fname = document.getElementById('fname-signup').value; 
    const lname = document.getElementById('lname-signup').value; 
    const username = document.getElementById('username-signup').value; 
    const email = document.getElementById('email-signup').value; 
    const password = document.getElementById('password-signup').value; 
    const data = new FormData(); data.append('fname', fname); data.append('lname', lname); data.append('username', username); data.append('email', email); data.append('password', password); 
    // Debug: Log the FormData to verify contents
     for (let pair of data.entries()) { console.log(pair[0] + ": " + pair[1]); } 
     // AJAX request to login.php to register the account 
     fetch('/infosec/login.php', { method: 'POST', body: data }) .then(response => response.json()) 
     // Parse the JSON response from PHP 
     .then(data => { if (data.success) { 
        popupForm1.style.display = 'none'; 
        console.log('Registration successful!'); 
        alert('Registration successful! Please log in.'); 
    } else {
        alert('Registration failed: ' + data.message); 
    } }) .catch(error => { console.error('Error during registration:', error); }); });
});
