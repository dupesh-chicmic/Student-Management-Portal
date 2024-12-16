<?php
include '../controller/Database.php';
$con = Database::connectDB(); 
$course_id = isset($_GET['id']) ? $_GET['id'] : null;
$sql = "SELECT c.course_name, t.topic_name FROM courses c INNER JOIN topics t ON c.id = t.course_id WHERE c.id = '$course_id'";
$result = $con->query($sql);
$topics = [];
while($row = $result->fetch_assoc()){
    if(!isset($course_name)){
        $course_name = $row['course_name'];
    }
    $topics[] = $row['topic_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<div class="container mt-3">
    <?php include './header.php' ?>
</div>
<div class="container">
    <div class="row mt-5">
        <div class="col">
            <h1>Course Details</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h5><strong>Course Name:</strong> <?= $course_name ?></h5>
        </div>
        <!-- <div class="col">
            <h5 class="ms-5"><strong class="ms-3">Email:</strong> <?= $student_details['email'] ?></h5>
        </div> -->
    </div>
    <div class="row mt-4">
        <div class="col">
            <h5 class="ms-3"><strong>Topics:</strong> <?php 
            foreach($topics as $topic){
                ?>
                <ul class="mt-4"><li>
                <?php
                echo $topic;
                ?>
                </li>
                </ul>
                <?php
            }
            ?></h5>
        </div>
        <!-- <div class="col">
            <h5><strong>Date of Birth:</strong> <?= $student_details['dob'] ?></h5>
        </div> -->
    </div>
</body>
</html>