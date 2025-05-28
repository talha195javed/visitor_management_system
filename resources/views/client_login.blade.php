<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal | Secure Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-container {
            max-width: 450px;
            margin: auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .login-container:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .login-header h2::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 3px;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .form-floating label {
            color: #6c757d;
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .btn-login:hover {
            background: linear-gradient(to right, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .forgot-password {
            text-align: right;
            margin-top: 0.5rem;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .forgot-password a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider-text {
            padding: 0 10px;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .bg-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .bg-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 40px;
            height: 40px;
            background-color: rgba(67, 97, 238, 0.1);
            bottom: -160px;
            animation: square 25s infinite;
            transition-timing-function: linear;
            border-radius: 5px;
        }

        .bg-bubbles li:nth-child(1) {
            left: 10%;
        }

        .bg-bubbles li:nth-child(2) {
            left: 20%;
            width: 80px;
            height: 80px;
            animation-delay: 2s;
            animation-duration: 17s;
        }

        .bg-bubbles li:nth-child(3) {
            left: 25%;
            animation-delay: 4s;
        }

        .bg-bubbles li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-duration: 22s;
            background-color: rgba(67, 97, 238, 0.15);
        }

        .bg-bubbles li:nth-child(5) {
            left: 70%;
        }

        .bg-bubbles li:nth-child(6) {
            left: 80%;
            width: 120px;
            height: 120px;
            animation-delay: 3s;
            background-color: rgba(67, 97, 238, 0.1);
        }

        .bg-bubbles li:nth-child(7) {
            left: 32%;
            width: 160px;
            height: 160px;
            animation-delay: 7s;
        }

        .bg-bubbles li:nth-child(8) {
            left: 55%;
            width: 20px;
            height: 20px;
            animation-delay: 15s;
            animation-duration: 40s;
        }

        .bg-bubbles li:nth-child(9) {
            left: 25%;
            width: 10px;
            height: 10px;
            animation-delay: 2s;
            animation-duration: 40s;
            background-color: rgba(67, 97, 238, 0.2);
        }

        .bg-bubbles li:nth-child(10) {
            left: 90%;
            width: 160px;
            height: 160px;
            animation-delay: 11s;
        }

        @keyframes square {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-1000px) rotate(600deg);
            }
        }

        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .floating-label input {
            padding: 15px 15px 10px 15px;
        }

        .floating-label label {
            position: absolute;
            top: 15px;
            left: 15px;
            color: #999;
            transition: all 0.3s;
            pointer-events: none;
        }

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            top: 5px;
            left: 10px;
            font-size: 0.75rem;
            color: var(--primary-color);
            background: white;
            padding: 0 5px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }

        .password-container {
            position: relative;
        }
    </style>
</head>
<body>
<div class="bg-bubbles">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</div>

<div class="container">
    <div class="login-container animate__animated animate__fadeIn">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to access your client portal</p>
        </div>

        <form id="clientLoginForm">
            @csrf

            <div class="floating-label">
                <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                <label for="email">Email Address</label>
                <i class="fas fa-envelope input-icon"></i>
            </div>

            <div class="floating-label">
                <div class="password-container">
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <span class="password-toggle" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </span>
                </div>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot password?</a>
            </div>

            <div id="loginError" class="alert alert-danger d-none mt-3" role="alert"></div>

            <button type="submit" class="btn btn-primary w-100 btn-login mt-3">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>

            <div class="divider">
                <span class="divider-text">OR CONTINUE WITH</span>
            </div>

            <div class="social-login">
                <a href="#" class="social-btn" style="background: #3b5998;">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn" style="background: #dd4b39;">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-btn" style="background: #1da1f2;">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-btn" style="background: #0077b5;">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>

            <div class="text-center">
                <p class="mb-0">Don't have an account? <a href="#" style="color: var(--primary-color);">Sign up</a></p>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Password toggle functionality
        $('#togglePassword').click(function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        // Auto-redirect if already logged in
        if (localStorage.getItem('client_logged_in') === 'true') {
            const redirectUrl = sessionStorage.getItem('intended_url') || '/';
            window.location.href = redirectUrl;
        }

        $('#clientLoginForm').on('submit', function(e) {
            e.preventDefault();
            $('#loginError').addClass('d-none');

            $.ajax({
                url: '/client/login',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Store client ID in localStorage
                        localStorage.setItem('client_id', response.client_id);
                        localStorage.setItem('client_logged_in', 'true');

                        // Create a loading effect before redirect
                        $('.login-container').addClass('animate__animated animate__fadeOut');
                        setTimeout(() => {
                            window.location.href = response.redirect || '/';
                        }, 500);
                    } else {
                        $('#loginError').text('Invalid credentials. Please try again.').removeClass('d-none');
                        $('.login-container').addClass('animate__animated animate__shakeX');
                        setTimeout(() => {
                            $('.login-container').removeClass('animate__animated animate__shakeX');
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#loginError').text(errorMsg).removeClass('d-none');
                    $('.login-container').addClass('animate__animated animate__shakeX');
                    setTimeout(() => {
                        $('.login-container').removeClass('animate__animated animate__shakeX');
                    }, 1000);
                }
            });
        });

        // Check if we have an intended URL in session storage
        const intendedUrl = sessionStorage.getItem('intended_url');
        if (intendedUrl && intendedUrl !== '/client/login') {
            sessionStorage.removeItem('intended_url');
            if (localStorage.getItem('client_logged_in') === 'true') {
                window.location.href = intendedUrl;
            }
        }
    });
</script>
@if(session('no_subscription'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'No Active Subscription',
        html: `You do not have any active subscription. Please contact Admin or buy a New Package from <a href="https://smartvisitor.io/home" target="_blank" style="color:#3085d6; text-decoration:underline;">Main Page</a>.`,
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false,
    });
</script>
@endif
</body>
</html>
