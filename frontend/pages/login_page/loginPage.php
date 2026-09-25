<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduManage Coaching System</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginStyle.css">
</head>

<body>
    <!-- HEADER -->
    <header class="all_header">
        <div class="cap">
            <img class="cap_image" src="cap.svg" alt="EduManage Logo">
        </div>
        <div class="headline">
            <h1>EduManage</h1>
            <p>Management Portal Access</p>
        </div>
    </header>

    <!-- MAIN FORM CARD -->
    <main class="main">
        <form id="loginForm">
            <!-- SELECT ROLE OPTION -->
            <div class="role_div">
                <label for="user_role">Select Role</label>
                <select id="user_role" required>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <!-- EMAIL INPUT -->
            <div class="email_div">
                <label for="email_address">Email Address</label>
                <input type="email" id="email_address" placeholder="name@example.com" required>
            </div>

            <!-- PASSWORD INPUT WITH SHOW/HIDE -->
            <div class="password_div">
                <div class="password_header_row">
                    <label for="password">Password</label>
                    <a href="#" class="forgot_link" onclick="alert('Please contact the System Administrator to reset your password.')">Forgot password?</a>
                </div>
                <div class="password_wrapper">
                    <input type="password" id="password" placeholder="Enter your password" required>
                    <button type="button" id="togglePassword" class="toggle_btn" aria-label="Toggle password visibility">
                        <img src="eye.svg" id="eyeIcon" alt="Show/Hide Password">
                    </button>
                </div>
            </div>

            <!-- REMEMBER ME -->
            <div class="remember_check">
                <input type="checkbox" id="checkbox">
                <label for="checkbox">Remember this device</label>
            </div>

            <!-- SIGN IN BUTTON -->
            <div class="sign_in_button">
                <button type="submit" id="submitBtn">Sign In</button>
            </div>
        </form>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="contract">
            <span class="DHA">Don't have an account?</span>
            <a href="mailto:admin@edumanage.com" class="CA">Contact Administrator</a>
        </div>
        <div class="policy">
            <a href="#">Privacy Policy</a>
            <span>•</span>
            <a href="#">Terms of Service</a>
            <span>•</span>
            <a href="#">Help Center</a>
        </div>
    </footer>

    <!-- JAVASCRIPT -->
    <script>
        // 1. Password Show/Hide Toggle
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });

        // 2. Form Submission & Role-based Redirection
        const loginForm = document.getElementById("loginForm");
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const role = document.getElementById("user_role").value;

            if (role === "admin") {
                window.location.href = "../Admin DashBoard/admin_dashboard.php";
            } else if (role === "teacher") {
                window.location.href = "../Teacher DashBoard/teacher_dashboard.php";
            } else if (role === "student") {
                window.location.href = "../Student DashBoard/student_dashboard.php";
            }
        });
    </script>
</body>

</html>