<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Professional Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        /* Main Container */
        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 40px;
            margin: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        /* Header Section */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #7f8c8d;
            font-size: 15px;
        }

        /* Logo */
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 14px;
        }

        .input-field {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        .input-field:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #7f8c8d;
            font-size: 18px;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: #7f8c8d;
            font-size: 18px;
        }

        /* Remember Me & Forgot Password */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-password a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #2980b9, #27ae60);
            transform: translateY(-2px);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #95a5a6;
            font-size: 14px;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider::before {
            margin-right: 15px;
        }

        .divider::after {
            margin-left: 15px;
        }

        /* Social Login */
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .social-btn:hover {
            transform: translateY(-3px);
        }

        .google {
            background: #db4437;
        }

        .facebook {
            background: #4267B2;
        }

        .twitter {
            background: #1DA1F2;
        }

        /* Sign Up Link */
        .signup-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
        }

        .signup-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .signup-link a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <div class="logo">P</div>
        <h2>Welcome Back</h2>
        <p>Sign in to access your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required>
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
        </div>

        <div class="form-options">
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <div class="forgot-password">
                <a href="#" id="forgotPasswordLink">Forgot password?</a>
            </div>
        </div>

        <button type="submit" class="submit-btn">Sign In</button>

        <div class="divider">or continue with</div>

        <div class="social-login">
            <div class="social-btn google" id="googleLogin">
                <i class="fab fa-google"></i>
            </div>
            <div class="social-btn facebook" id="facebookLogin">
                <i class="fab fa-facebook-f"></i>
            </div>
            <div class="social-btn twitter" id="twitterLogin">
                <i class="fab fa-twitter"></i>
            </div>
        </div>

        <div class="signup-link">
            Don't have an account? <a href="#" id="signupLink">Sign up</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Toggle password visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });

    // Add focus effects
    const inputs = document.querySelectorAll('.input-field');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.querySelector('.input-icon').style.color = '#3498db';
        });

        input.addEventListener('blur', function() {
            this.parentElement.querySelector('.input-icon').style.color = '#7f8c8d';
        });
    });

    // Forgot Password Alert
    document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Reset Password',
            html: '<div style="text-align:center;">' +
                '<i class="fas fa-user-shield" style="font-size:60px;color:#3498db;margin-bottom:20px;"></i>' +
                '<p style="font-size:16px;margin-bottom:20px;">Please contact your system administrator to reset your account password.</p>' +
                '</div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3498db',
            customClass: {
                popup: 'animated bounceIn'
            }
        });
    });

    // Social Login Alerts
    const socialButtons = ['googleLogin', 'facebookLogin', 'twitterLogin'];
    socialButtons.forEach(buttonId => {
        document.getElementById(buttonId).addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Account Creation Restricted',
                html: '<div style="text-align:center;">' +
                    '<i class="fas fa-exclamation-triangle" style="font-size:60px;color:#e74c3c;margin-bottom:20px;"></i>' +
                    '<p style="font-size:16px;margin-bottom:20px;">You are not allowed to create accounts via social login. Please contact your administrator for access.</p>' +
                    '</div>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#e74c3c',
                customClass: {
                    popup: 'animated shake'
                }
            });
        });
    });

    // Sign Up Link Alert
    document.getElementById('signupLink').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Account Creation Restricted',
            html: '<div style="text-align:center;">' +
                '<i class="fas fa-exclamation-circle" style="font-size:60px;color:#f39c12;margin-bottom:20px;"></i>' +
                '<p style="font-size:16px;margin-bottom:20px;">You are not allowed to create accounts. Please contact your administrator for access.</p>' +
                '</div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f39c12',
            customClass: {
                popup: 'animated pulse'
            }
        });
    });
</script>
</body>
</html>
