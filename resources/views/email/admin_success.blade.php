<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Hello,</h2>
    <p>We are pleased to inform you that your visitor has been successfully registered in our Visitor Management System and waiting for you. Please attend him or request HR to attend him</p>
    <p><strong>Visit Details:</strong></p>
    <ul>
        <li><strong>Name:</strong> {{ $visitor->full_name }}</li>
        <li><strong>Email:</strong> {{ $visitor->email }}</li>
        <li><strong>Purpose:</strong> {{ $visitor->role }}</li>
        <li><strong>Contact Number:</strong> {{ $visitor->phone }}</li>
    </ul>
    <p>Thank you for your time!</p>
    <p class="footer">Best regards, <br> Visitor Management System</p>
</div>
</body>
</html>
