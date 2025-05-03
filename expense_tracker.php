<?php

include_once("connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch expenses of the logged-in user
$expenses_query = "SELECT e.expense_id, c.category_name, e.amount, e.expense_description, e.expense_date
                   FROM tbl_expenses e
                   JOIN tbl_categories c ON e.category_id = c.category_id
                   WHERE e.user_id = ?";

$stmt_expenses = $conn->prepare($expenses_query);
$stmt_expenses->bind_param("i", $user_id);
$stmt_expenses->execute();
$stmt_expenses->bind_result($expense_id, $category_name, $amount, $expense_description, $expense_date);

$expenses = [];
while ($stmt_expenses->fetch()) {
    $expenses[] = [
        'expense_id' => $expense_id,
        'category_name' => $category_name,
        'amount' => $amount,
        'expense_description' => $expense_description,
        'expense_date' => $expense_date
    ];
}

$stmt_expenses->close();

// Handle Add Expense
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $description = $_POST['expense_description'];
    $expense_date = $_POST['expense_date'];

    // Insert new expense into the database
    $insert_query = "INSERT INTO tbl_expenses (user_id, category_id, amount, expense_description, expense_date) 
                     VALUES (?, ?, ?, ?, ?)";

    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("iiiss", $user_id, $category_id, $amount, $description, $expense_date);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Redirect to the same page to see the new expense
    header("Location: expense_tracker.php");
    exit();
}

// Handle Delete Expense
if (isset($_GET['delete_expense_id'])) {
    $expense_id_to_delete = $_GET['delete_expense_id'];

    $delete_query = "DELETE FROM tbl_expenses WHERE expense_id = ?";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bind_param("i", $expense_id_to_delete);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Redirect to the same page to see the updated list
    header("Location: expense_tracker.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Expense Tracker</span>
        <div class="d-flex">
            <span class="navbar-text me-3 text-white">
                Welcome, <?= htmlspecialchars($_SESSION['username']) ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <!-- Back to Dashboard Button -->
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <h1 class="mb-4">Your Expenses</h1>

    <!-- Add Expense Form -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Add New Expense</h5>
            <form method="POST" action="expense_tracker.php">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <?php
                        // Fetch categories for the dropdown
                        $categories_query = "SELECT category_id, category_name FROM tbl_categories";
                        $stmt_categories = $conn->prepare($categories_query);
                        $stmt_categories->execute();
                        $stmt_categories->bind_result($category_id, $category_name);

                        while ($stmt_categories->fetch()) {
                            echo "<option value='$category_id'>$category_name</option>";
                        }

                        $stmt_categories->close();
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" required step="0.01">
                </div>
                <div class="mb-3">
                    <label for="expense_description" class="form-label">Description</label>
                    <input type="text" name="expense_description" id="expense_description" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="expense_date" class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" id="expense_date" class="form-control" required>
                </div>
                <button type="submit" name="add_expense" class="btn btn-primary">Add Expense</button>
            </form>
        </div>
    </div>

    <!-- List of Expenses -->
    <h3>Expense List</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $expense): ?>
            <tr>
                <td><?= htmlspecialchars($expense['category_name']) ?></td>
                <td>$<?= number_format($expense['amount'], 2) ?></td>
                <td><?= htmlspecialchars($expense['expense_description']) ?></td>
                <td><?= htmlspecialchars($expense['expense_date']) ?></td>
                <td>
                    <a href="expense_tracker.php?delete_expense_id=<?= $expense['expense_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
