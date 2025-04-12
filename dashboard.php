<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Expense Tracker</span>
        <div class="d-flex">
            <span class="navbar-text me-3 text-white">
                Welcome, <?= htmlspecialchars($username) ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Income</h5>
                    <p class="card-text">$0.00</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses</h5>
                    <p class="card-text">$0.00</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Balance</h5>
                    <p class="card-text">$0.00</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h3>Welcome to your Expense Dashboard</h3>
        <p>HAHAHAHA ND MAG CONNECT AY GIDEON</p>
        <a href="index.php" class="btn btn-primary">Go to Expense Tracker</a>
    </div>
</div>

</body>
</html>
