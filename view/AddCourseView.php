<?php
session_start();
$error = $_SESSION['error'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css" />
    <script>
        function addTopicInput() {
            const topicsContainer = document.getElementById("topicsContainer");
            const newTopicDiv = document.createElement("div");
            newTopicDiv.classList.add("mt-2");
            newTopicDiv.innerHTML = `
                <div class="input-group">
                    <input type="text" name="topics[]" class="form-control mt-2" placeholder="Topic Name">
                    <button type="button" class="btn btn-danger input-group-text" onclick="removeTopicInput(this)">x</button>
                </div>
            `;
            topicsContainer.appendChild(newTopicDiv);
        }

        function removeTopicInput(button) {
            const topicDiv = button.parentElement;
            topicDiv.remove();
        }
    </script>
</head>
<body>
<div class="container mt-3">
    <?php include './header.php' ?>
</div>
<div class="container w-75 mt-5 p-5 pt-3 rounded bg-light">
    <h1 class="text-center mt-2 mb-5">Add Course and Topics</h1>
    <form id="registrationForm" action="../controller/AddCourse.php" method="POST" class="mt-5" enctype="multipart/form-data">

    <label>Course Name:</label>
        <input type="text" name="course_name" class="form-control mt-2" placeholder="Course Name" 
            value="<?= isset($_SESSION['form_data']['course_name']) ? $_SESSION['form_data']['course_name'] : '' ?>">
        <?php if (isset($error['empty_course_name'])): ?>
            <span class="text-danger"><?= $error['empty_course_name'] ?></span>
        <?php endif; 
        echo "</br>";
        ?>
        <label>Topics Name:</label>
        <div id="topicsContainer">
            <?php
            $topics = isset($_SESSION['form_data']['topics']) ? $_SESSION['form_data']['topics'] : [''];
            foreach ($topics as $index => $topic): ?>
                <div class="mt-2">
                    <div class="input-group">
                        <input type="text" name="topics[]" class="form-control" placeholder="Topic Name" 
                            value="<?= htmlspecialchars($topic) ?>">
                        <button type="button" class="btn btn-danger input-group-text" onclick="removeTopicInput(this)">x</button>
                    </div>
                    <?php if (isset($error['empty_topic_' . $index])): ?>
                        <span class="text-danger"><?= $error['empty_topic_' . $index] ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mt-2" onclick="addTopicInput()">Add Topic</button>

        <div class="mt-4">
            <input class="btn btn-primary w-100" type="submit" name="register" value="Add">
        </div>
    </form>
</div>

<?php 
    unset($_SESSION['error']);
    unset($_SESSION['form_data']);
?>
</body>
</html>
