<?php
session_start();
include '../controller/Database.php';

$error = $_SESSION['error'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];

$id = $_GET['id'] ?? '';
$con = Database::connectDB();
    $sql = "SELECT * FROM students WHERE id = '$id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<div class="container mt-3">
        <?php include './header.php' ?>
    </div>
<div class="container w-75 mt-5 p-5 pt-3 rounded bg-light">
    <h1 class="text-center mt-2 mb-5">Update Student Information</h1>
    <form id="updateForm" action="../controller/UpdateStudent.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['id'] ?? ''?>">

        <label>Upload Image:</label>
        <input type="file" name="image" class="form-control">
        <?php if (isset($error['image'])): ?>
            <span class="text-danger"><?= $error['image'] ?></span>
        <?php endif; ?>

        <div class="mt-3">
            <label>Enter First Name:</label>
            <input type="text" name="first_name" placeholder="First Name" class="form-control"
            value="<?= $row['first_name'] ?? '' ?>">
            <?php if (isset($error['invalid_first_name'])): ?>
                <span class="text-danger"><?= $error['invalid_first_name'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter Last Name:</label>
            <input type="text" name="last_name" placeholder="Last Name" class="form-control" value="<?= $row['last_name'] ?? '' ?>">
            <?php if (isset($error['invalid_last_name'])): ?>
                <span class="text-danger"><?= $error['invalid_last_name'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter Email:</label>
            <input type="email" name="email" placeholder="Email" class="form-control"
            value="<?= $row['email'] ?? '' ?>">
            <?php if (isset($error['invalid_email'])): ?>
                <span class="text-danger"><?= $error['invalid_email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter Phone:</label>
            <input type="tel" name="phone" placeholder="Phone" class="form-control"
            value="<?= $row['phone'] ?? '' ?>">
            <?php if (isset($error['invalid_phone'])): ?>
                <span class="text-danger"><?= $error['invalid_phone'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter DOB:</label>
            <input type="date" name="dob" class="form-control" 
            value="<?= $row['dob'] ?? '' ?>">
        </div>

        <div class="mt-4">
            <input class="btn btn-primary w-100" type="submit" name="update" value="Update">
        </div>
    </form>
</div>

<script src="../js/UpdateStudentValidation.js"></script>
</body>
</html>
