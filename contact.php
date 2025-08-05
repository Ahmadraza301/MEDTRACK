<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Landing Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            background-color:rgb(192, 243, 249);
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 1000px;
            position: relative;
            text-align: center;
        }
        .profile-pic {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 100px;
            height: 100px;
            border-radius: 100%;
            object-fit: cover;
        }
        h2 {
            margin-top: 20px;
        }
        .info {
            text-align: left;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="./assets/img/ahmad.jpeg" alt="Profile Photo" class="profile-pic">
        <h2>Ahmad Raza Ansari</h2>
        <div class="info">
            <p><strong>Email:</strong> 321ahmad0042@gmail.com</p>
            <p><strong>Education:</strong> B.E in IT, Don Bosco Institute of Technology</p>
            <p><strong>Address:</strong> Mumbai, India</p>
            <p><strong>Mobile:</strong> +91 7058930166</p>
            <p><strong>Location:</strong> Mumbai, Maharashtra, India</p>
        </div>
    </div>
</body>
</html>