<?php
session_start();
include '../controller/Database.php';

$id = $_GET['id'] ?? '';
$con = Database::connectDB();
$sql = "SELECT t.id AS topic_id, c.id AS course_id, c.course_name, t.topic_name 
        FROM courses c 
        INNER JOIN topics t ON c.id = t.course_id 
        WHERE c.id = '$id'";

$result = $con->query($sql);
$topics = [];
while ($row = $result->fetch_assoc()) {
    if (!isset($course_name)) {
        $course_name = $row['course_name'];
    }
    $topics[] = [
        'topic_id' => $row['topic_id'], 
        'topic_name' => $row['topic_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course Information</title>
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
    <h1 class="text-center mt-2 mb-5">Edit Course Information</h1>
    <form id="updateForm" action="../controller/EditCourse.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="course_id" value="<?= $id ?? '' ?>">

        <div class="mt-3">
            <label class="my-2">Course Name:</label>
            <input type="text" name="course_name" placeholder="Course Name" class="form-control mb-3"
            value="<?= $course_name ?? '' ?>">
        </div>

        <label class="my-2">Topics:</label>
        <?php
        foreach ($topics as $topic) {
            ?>
            <input type="hidden" name="topic_ids[]" value="<?= $topic['topic_id'] ?>">
            <input type="text" name="topics[]" class="form-control mb-3" value="<?= $topic['topic_name'] ?>" placeholder="Topic Name">
            <?php
        }
        ?>

        <div class="mt-4">
            <input class="btn btn-primary w-100" type="submit" name="updateCourse" value="Update">
        </div>
    </form>
</div>

</body>
</html>
