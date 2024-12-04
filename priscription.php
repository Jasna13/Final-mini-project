<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Priscription Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            height:130px;
        }
        nav{
            background-color:#333;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            /* display: inline; */
            /* margin-right: 20px; */
        }
        nav ul li a {
            color: white;
            text-decoration: none;
        }
        section {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #444;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        button.delete {
            background-color: #dc3545;
        }
        button.delete:hover {
            background-color: #c82333;
        }
        img {
            max-width: 80px;
            height: auto;
            border-radius: 5px;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: calc(100% - 16px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #444;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
        }
        /* Style for the Prescription Requirement Column */
.prescription-status {
    text-align: center;
    padding: 10px;
    font-weight: bold;
    border-radius: 5px;
}

/* "Yes" Style (indicating prescription required) */
.prescription-status.yes {
    background-color: #28a745; /* Green color */
    color: black;
}

/* "No" Style (indicating no prescription required) */
.prescription-status.no {
    background-color: #dc3545; /* Red color */
    color: white;
}

    </style>
</head>
<body>
<nav>
<header>
    <h1>Priscription Management</h1>
    <ul>
        <li><a href="index2.php">Dashboard</a></li>
        <li><a href="products.php">Product Management</a></li>
        <li><a href="stock.php">Stock Management</a></li>
        <li><a href="order.php">Order Mangement</a></li>   
        <li><a href="user.php">User Mangement</a></li> 
        <li><a href="priscription.php">Prescription Mangement</a></li>
        <li><a href="contact.php">Contact Mangement</a></li>   
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>
</nav>
<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'medico_shop');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all prescriptions
$query = "SELECT p.id, p.image AS prescription_image, p.status, u.username AS user_name, pr.name AS product_name 
          FROM prescriptions p
          JOIN user u ON p.user_id = u.uid
          JOIN products pr ON p.product_id = pr.id";
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

// Fetch all prescriptions into an array
$prescriptions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prescriptions[] = $row;
    }
}

$conn->close();
?>

<!-- Display Prescription List -->
<table class="table">
    <thead>
        <tr>
            <th>Prescription ID</th>
            <th>User Name</th>
            <th>Product</th>
            <th>Prescription Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prescriptions as $prescription): ?>
            <tr>
                <td><?= htmlspecialchars($prescription['id']) ?></td>
                <td><?= htmlspecialchars($prescription['user_name']) ?></td>
                <td><?= htmlspecialchars($prescription['product_name']) ?></td>
                <td><img src="<?= htmlspecialchars($prescription['prescription_image']) ?>" alt="Prescription Image" width="100"></td>
                <td><?= ucfirst(htmlspecialchars($prescription['status'])) ?></td>
                <td>
                    <?php if ($prescription['status'] == 'pending'): ?>
                        <a href="approve_priscription.php?id=<?= $prescription['id'] ?>" class="btn btn-success">Approve</a>
                        <a href="reject_prescription.php?id=<?= $prescription['id'] ?>" class="btn btn-danger">Reject</a>
                    <?php else: ?>
                        <span class="text-muted">No Actions Available</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<style>
    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
        font-size: 24px;
    }

    .container {
        width: 90%;
        margin: 0 auto;
        padding: 20px;
    }

    /* Table Styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #444;
        color: white;
    }

    .table tr:hover {
        background-color: #f1f1f1;
    }

    .table img {
        max-width: 100px;
        height: auto;
    }

    .table .btn {
        padding: 8px 15px;
        border-radius: 4px;
        color: #fff;
        text-decoration: none;
        margin-right: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .table .btn-success {
        background-color: #28a745;
    }

    .table .btn-success:hover {
        background-color: #218838;
    }

    .table .btn-danger {
        background-color: #dc3545;
    }

    .table .btn-danger:hover {
        background-color: #c82333;
    }

    .text-muted {
        color: #6c757d;
    }

    /* Responsive Styling */
    @media (max-width: 768px) {
        .table, .table thead, .table tbody, .table th, .table td, .table tr {
            display: block;
        }

        .table th {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .table tr {
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .table td {
            border: none;
            border-bottom: 1px solid #ddd;
            position: relative;
            padding-left: 50%;
        }

        .table td:before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            font-weight: bold;
            color: #333;
        }
    }
</style>
