function submitForm() {
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm-password').value;
    var errorMessageElement = document.getElementById('password-validation-error');

    // Check if reCAPTCHA is not verified
    var recaptchaResponse = grecaptcha.getResponse();
    if (!recaptchaResponse) {
        errorMessageElement.innerHTML = '<span style="color: red;">reCAPTCHA verification failed. Please check the reCAPTCHA box.</span>';
        return false;
    }

    if (!username || !email || !password || !confirmPassword) {
        errorMessageElement.innerHTML = '<span style="color: red;">Please fill out all required fields before submitting.</span>';
        return false;
    }

    if (password !== confirmPassword) {
        errorMessageElement.innerHTML = '<span style="color: red;">Passwords do not match.</span>';
        return false;
    }

    if (password.includes(username) || password.includes(email)) {
        errorMessageElement.innerHTML = '<span style="color: red;">Password cannot contain username or email.</span>';
        return false;
    }

       
        if (!checkPasswordCriteria(password)) {
            errorMessageElement.innerHTML = '<span style="color: red;">Password does not meet criteria.</span>';
            return false;
        }

        // All checks passed, proceed with signup logic
        alert('Form submitted successfully!');
        // You can add further logic here to submit the form to the server if needed
        return true;
    }

    function checkPasswordCriteria(password) {
        // Define criteria
        var minLength = 8;
        var hasUppercase = /[A-Z]/.test(password);
        var hasLowercase = /[a-z]/.test(password);
        var hasNumber = /\d/.test(password);
        var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        // Check if all criteria are met
        return (
            password.length >= minLength &&
            hasUppercase &&
            hasLowercase &&
            hasNumber &&
            hasSpecialChar
        );
    }

    function checkPasswordMatch() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm-password').value;
        var matchErrorElement = document.getElementById('password-match-error');

        if (password === confirmPassword) {
            matchErrorElement.innerHTML = '<span style="color: green;">Passwords match</span>';
        } else {
            matchErrorElement.innerHTML = '<span style="color: red;">Passwords do not match</span>';
        }
    }

    function checkPasswordStrength() {
        var password = document.getElementById('password').value;
        var strengthMeter = document.getElementById('password-strength');
        var strengthMeterBars = document.querySelectorAll('.strength-bar');

        // Reset the meter and bars
        strengthMeter.innerHTML = '';
        strengthMeterBars.forEach(bar => bar.style.backgroundColor = 'transparent');

        // Define criteria
        var minLength = 8;
        var hasUppercase = /[A-Z]/.test(password);
        var hasLowercase = /[a-z]/.test(password);
        var hasNumber = /\d/.test(password);
        var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        // Check each criteria and update the meter
        if (password.length >= minLength) {
            strengthMeter.innerHTML += '<span style="color: green;">&#9989 Length is at least ' + minLength + ' characters.</span><br>';
        } else {
            strengthMeter.innerHTML += '<span style="color: red;">&#10060 Length should be at least ' + minLength + ' characters.</span><br>';
        }

        if (hasUppercase) {
            strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains uppercase letters.</span><br>';
        } else {
            strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one uppercase letter.</span><br>';
        }

        if (hasLowercase) {
            strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains lowercase letters.</span><br>';
        } else {
            strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one lowercase letter.</span><br>';
        }

        if (hasNumber) {
            strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains numbers.</span><br>';
        } else {
            strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one number.</span><br>';
        }

        if (hasSpecialChar) {
            strengthMeter.innerHTML += '<span style="color: green;">&#9989 Contains special characters.</span><br>';
        } else {
            strengthMeter.innerHTML += '<span style="color: red;">&#10060 Should contain at least one special character.</span><br>';
        }

        // Calculate overall strength and update the bars
        var overallStrength = [hasUppercase, hasLowercase, hasNumber, hasSpecialChar].filter(Boolean).length;
        switch (overallStrength) {
            case 0:
            case 1:
                strengthMeter.innerHTML += '<span style="font-size:30px; font-weight: bold; color: red;">&#128078 Weak</span>';
                break;
            case 2:
            case 3:
                strengthMeter.innerHTML += '<span style="font-size:30px; font-weight: bold; color: orange;">&#128077 Medium</span>';
                break;
            case 4:
                strengthMeter.innerHTML += '<span style="font-size:30px; font-weight: bold; color: green;"> &#128170 Strong</span>';
                break;
        }

        for (var i = 0; i < overallStrength; i++) {
            strengthMeterBars[i].style.backgroundColor = 'green';
        }
    }

    function checkPasswordValidity() {
        var username = document.getElementById('username').value.toLowerCase();
        var email = document.getElementById('email').value.toLowerCase();
        var password = document.getElementById('password').value;
        var validityErrorElement = document.getElementById('password-validation-error');

        if (password.includes(username) || password.includes(email)) {
            validityErrorElement.innerHTML = '<span style="color: red;">Password cannot contain username or email</span>';
        } else {
            validityErrorElement.innerHTML = '';
        }
    }