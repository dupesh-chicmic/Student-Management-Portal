<?php
session_start();
include '../controller/Database.php';
$con = Database::connectDB(); 
$error = $_SESSION['error'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="../assets/css/style.css" />
    </head>
    <style>
        .error{
            color: red;
        }
        </style>
<body>
    
    <div class="container mt-3">
        <?php include './header.php' ?>
    </div>
    <div class="container w-75 mt-5 p-5 pt-3 rounded bg-light">
    <h1 class="text-center mt-2 mb-5">Register New Sudent</h1>
    <form id="registrationForm" action="../controller/AddStudent.php" method="POST" class="mt-5" enctype="multipart/form-data">
        <label>Upload Image:</label>
        <input type="file" name="image" class="form-control">
        <?php if (isset($error['image'])): ?>
            <span class="text-danger"><?= $error['image'] ?></span>
        <?php endif; ?>

        <div class="mt-3">
            <label>Enter First Name:</label>
            <input type="text" name="first_name" placeholder="First Name" class="form-control"
            value="<?= $form_data['first_name'] ?? '' ?>">
            <?php if (isset($error['invalid_first_name'])): ?>
                <span class="text-danger"><?= $error['invalid_first_name'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter Last Name:</label>
            <input type="text" name="last_name" placeholder="Last Name" class="form-control" value="<?= $form_data['last_name'] ?? '' ?>">
            <?php if (isset($error['invalid_last_name'])): ?>
                <span class="text-danger"><?= $error['invalid_last_name'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter Email:</label>
            <input type="email" name="email" placeholder="Email" class="form-control"
            value="<?= $form_data['email'] ?? '' ?>">
            <?php if (isset($error['invalid_email'])): ?>
                <span class="text-danger"><?= $error['invalid_email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter Phone:</label>
            <input type="tel" name="phone" placeholder="Phone" class="form-control"
            value="<?= $form_data['phone'] ?? '' ?>">
            <?php if (isset($error['invalid_phone'])): ?>
                <span class="text-danger"><?= $error['invalid_phone'] ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-3">
            <label>Enter DOB:</label>
            <input type="date" name="dob" class="form-control" 
            value="<?= $form_data['dob'] ?? '' ?>" max="2015-12-31">
            <?php if (isset($error['invalid_dob'])): ?>
                <span class="text-danger"><?= $error['invalid_dob'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="mt-3">
                <label>Select Course:</label>
                <select name="course[]" multiple class="form-control">
                <?php    
                $courses = [];
                $sql = "SELECT * FROM courses";
                $result = $con->query($sql);
                while($row = $result->fetch_assoc()){
                    $courses[] = [
                        'id' => $row['id'],
                        'course_name' => $row['course_name']
                    ];
                }
                foreach($courses as $course){
                ?>
                <option value="<?php echo $course['id'] ?>"><?php echo $course['course_name'] ?></option>
                <?php
                }
                ?> 
                </select>
            </div>

        <div class="mt-4">
            <input class="btn btn-primary w-100" type="submit" name="register" value="Register">
        </div>
    </form>
</div>

<?php
session_destroy();
?>
<script src="../js/AddStudentValidation.js"></script>
</body>
</html>
