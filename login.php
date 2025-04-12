<?php
session_start();
include_once("connection.php");

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($email) && !empty($password)) {
        $result = $conn->query("SELECT user_id, username, password FROM tbl_users WHERE email = '$email'");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($password == $row['password']) {
                $_SESSION["user_id"] = $row['user_id'];
                $_SESSION["username"] = $row['username'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that email.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        .brand-title {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h3 class="brand-title">Expense Tracker</h3>

        <?php if (isset($_GET['logout'])): ?>
            <div class="alert alert-success">You have been logged out successfully.</div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="mt-3 text-center">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

</body>
</html>