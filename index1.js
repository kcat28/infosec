document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById('loginForm');
    const popupForm = document.getElementById('popupForm');
    const profileName = document.getElementById('profile-name');
    const profileEmail = document.getElementById('profile-email');
    const LOGIN_EXPIRY_KEY = 'loginExpiry';

    console.log("DOM fully loaded and parsed");

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

        fetch('/infosec/login.php', {
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
                } else {
                    alert('Invalid username or password.');
                }
            })
            .catch(error => {
                console.error('Error during login:', error);
            });
    });
});
