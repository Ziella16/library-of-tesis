document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.querySelector(".wrapper");
    const loginLink = document.querySelector(".btnLogin-popup");
    const registerLink = document.querySelector(".register-link");
    const closeButton = document.querySelector(".icon-close");

    loginLink.addEventListener("click", () => {
        wrapper.classList.add("active-popup");
        wrapper.classList.remove("active");
    });

    registerLink.addEventListener("click", (event) => {
        event.preventDefault();
        wrapper.classList.add("active");
    });

    closeButton.addEventListener("click", () => {
        wrapper.classList.remove("active-popup");
    });
});

function togglePasswordVisibility(passwordFieldId, iconElement) {
    const passwordInput = document.getElementById(passwordFieldId);
    const toggleIcon = iconElement.querySelector('ion-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.setAttribute('name', 'eye-off-outline');
    } else {
        passwordInput.type = 'password';
        toggleIcon.setAttribute('name', 'eye-outline');
    }
}


    registerForm.addEventListener('submit', (event) => {
        const username = document.getElementById('registerUsername').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;

        const usernamePattern = /^[A-Za-z]+$/;
        const emailPattern = /^[0-9]+@adtectaiping\.edu\.my$/;
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        let valid = true;
        let errorMessage = '';

        if (!usernamePattern.test(username)) {
            valid = false;
            errorMessage += 'Username must contain only alphabetic characters.\n';
        }

        if (!emailPattern.test(email)) {
            valid = false;
            errorMessage += 'Email must start with NDP and end with @adtectaiping.edu.my.\n';
        }

        if (!passwordPattern.test(password)) {
            valid = false;
            errorMessage += 'Password must be at least 8 characters long, one number, and one special character.\n';
        }

        if (!valid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });
