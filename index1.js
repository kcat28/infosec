document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const login = document.getElementById('login-btn');
    const signup = document.getElementById('signup-btn');
    const popupForm = document.getElementById('popupForm');
    const popupForm1 = document.getElementById('popupForm1');
    const profileName = document.getElementById('profile-name');
    const profileEmail = document.getElementById('profile-email');
    let isLoggedIn = false;
    console.log("successful DOM");

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

    // display login upon laod
    popupForm.style.display = 'flex';

    // login form submission
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();  // prevent page reload

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        const data = new FormData();
        data.append('action', 'login');
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

                    // Update profile section 
                    profileName.textContent = data.user.user_fname + ' ' + data.user.user_lname;
                    profileEmail.textContent = data.user.email;
                    console.log("Profile Name Element:", profileName);
                    console.log("Profile Email Element:", profileEmail);

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

    // signup form submission
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
            fetch("/infosec/login.php", { method: "POST", body: data })
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
    }




});
