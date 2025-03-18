<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.1.1/dist/tesseract.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: #0d6efd;
        }

        .navbar a {
            color: #fff;
        }

        .navbar a:hover {
            color: #dcdcdc;
        }

        .container-fluid {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .col-md-6 {
            flex: 1;
            padding: 0;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #4F46E5, #9333EA);
            color: white;
            padding: 30px;
            text-align: center;
            height: 100%;
        }

        .enter-button {
            border-radius: 50px;
            padding: 12px 40px;
            font-size: 18px;
            background-color: #fff;
            color: #9333EA;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .enter-button:hover {
            background-color: #9333EA;
            color: #fff;
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }

        .check-in-out-card {
            background-color: #f8f9fa;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            text-align: center;
        }

        .check-in-out-card h2 {
            font-size: 24px;
            font-weight: 600;
        }

        .check-in-out-btn {
            border-radius: 50px;
            font-weight: 600;
            padding: 15px 40px;
            font-size: 18px;
        }

        .check-in-btn {
            background-color: #28a745;
            color: #fff;
        }

        .check-out-btn {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm navbar-hidden">
    <div class="container">
        <a class="navbar-brand" href="{{ route('visitor.home') }}">
            <i class="fas fa-user-check me-2"></i>Visitor Management
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>


<div class="">
    @yield('content')
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
