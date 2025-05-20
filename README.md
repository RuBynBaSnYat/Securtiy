🔐 Web Security System
Welcome to the Web Security System project. This system is designed to demonstrate essential security features in modern web applications. Built using PHP, MySQL, HTML, CSS, and JavaScript, it focuses on protecting user data and preventing common security threats such as brute-force attacks and bots.

📌 Features
✅ Strong Password Policy
Enforces secure password requirements:

a. Minimum 8 characters

b. At least one uppercase letter

c. At least one lowercase letter

d. At least one number

e. At least one special character

✅ Google reCAPTCHA Integration
 Integrated Google reCAPTCHA v2 (Checkbox)
 Prevents automated bot attacks on:
 Login page
 Registration page

✅ Password Encryption with Bcrypt
a. Passwords are hashed using Bcrypt before storing in the database
b. Enhances protection against brute-force and rainbow table attacks

✅ Secure Password Reset Functionality
a. Password recovery via secure token-based email link
b. Implemented using PHPMailer
c. Ensures smooth and safe user experience during password recovery

🛠️ Technologies Used
Technology	Description
1. PHP = Backend scripting
2. MySQL = Database
3. HTML/CSS	= Frontend layout and styling
4. JavaScript =	Client-side validation
5. PHPMailer = Email functionality (reset links)
6. Google reCAPTCHA	= Bot prevention on forms
7. Bcrypt	= Secure password hashing
