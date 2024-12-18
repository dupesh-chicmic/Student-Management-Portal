<?php 
session_start();
$value = $_SESSION['flag'] ?? [];
include '../controller/Database.php';
$con = Database::ConnectDb();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
</head>
<body>
    <div class="container my-3">
        <?php include './header.php' ?>
        <div class="row">
            <div>
                <div class="content-header">
                    <h3>Student Management Portal</h3>
                    <a href="./AddStudentView.php" class="btn btn-success"> + Add New</a>
                </div>
            </div>
        </div>
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>file</th>
                    <th>first name</th>
                    <th>last name</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>dob</th>
                    <th>operations</th>
                </tr>
            </thead>
        </table>
    </div>

<script>
    let table = new DataTable('#myTable', {
        ajax: '../controller/data.php',
        processing: true,
        search: true,
        serverSide: true,
    });
</script>

</body>
</html>