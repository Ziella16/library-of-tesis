function sendVerificationCode() {
    const email = document.getElementById('email').value;

    if (email) {
        fetch('send_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'email': email,
            }),
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('message').innerText = result;
            document.getElementById('verification-form').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        document.getElementById('message').innerText = 'Please enter your email.';
    }
}

function verifyCode() {
    const email = document.getElementById('email').value;
    const code = document.getElementById('code').value;

    if (email && code) {
        fetch('verify_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'email': email,
                'code': code,
            }),
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('message').innerText = result;

            if (result === 'Code verified successfully!') {
                // Hide verification form on success
                document.getElementById('verification-form').style.display = 'none';
                // Possibly redirect to another page or show a success message
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        document.getElementById('message').innerText = 'Please enter the verification code.';
    }
}
