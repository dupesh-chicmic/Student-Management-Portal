<?php
session_start();
include '../controller/Database.php';

$con = Database::connectDB(); 

$student_id = isset($_GET['id']) ? $_GET['id'] : null;
$sql_show = "SELECT s.first_name, s.last_name, s.email, s.phone, s.dob, s.file, 
                    c.id AS course_id, c.course_name, t.id AS topic_id, t.topic_name, sc.is_completed AS course_status, 
                    st.is_completed AS topic_status
             FROM students s
             INNER JOIN students_courses sc ON s.id = sc.student_id
             INNER JOIN courses c ON sc.course_id = c.id
             INNER JOIN topics t ON c.id = t.course_id
             LEFT JOIN students_topics st ON st.student_id = s.id AND st.topic_id = t.id
             WHERE s.id = '$student_id'";

$result_show = $con->query($sql_show);
$student_details = null;

if ($result_show->num_rows > 0) {
    $row_show = $result_show->fetch_assoc();
    $student_details = [
        'first_name' => $row_show['first_name'],
        'last_name' => $row_show['last_name'],
        'email' => $row_show['email'],
        'phone' => $row_show['phone'],
        'dob' => $row_show['dob'],
        'file' => $row_show['file']
    ];
    $courses = [];

    do {
        $course_name = $row_show['course_name'];
        if (!isset($courses[$course_name])) {
            $courses[$course_name] = [
                'course_id' => $row_show['course_id'],
                'course_name' => $course_name,
                'status' => $row_show['course_status'] ? 'Completed' : 'In Progress',
                'topics' => []
            ];
        }

        $courses[$course_name]['topics'][] = [
            'topic_id' => $row_show['topic_id'],
            'topic_name' => $row_show['topic_name'],
            'is_completed' => $row_show['topic_status'] ? 'Completed' : 'In Progress'
        ];
    } while ($row_show = $result_show->fetch_assoc());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <style>
        .course-status {
            font-weight: bold;
        }
    </style>
    <script>
        let checkedTopics = [];
        let uncheckedTopics = [];
        const student_id = "<?php echo $student_id; ?>";
        function handleCheck(event) {
            const topicId = event.target.getAttribute('topic_id');

            if (event.target.checked) {
                $(event.target).parent().find('.chkbx').removeClass('text-danger').addClass('text-success').text('Completed');
                if (!checkedTopics.includes(topicId)) {
                    checkedTopics.push(topicId);
                }
                const uncheckedIndex = uncheckedTopics.indexOf(topicId);
                if (uncheckedTopics > -1) {
                    uncheckedTopics.splice(uncheckedIndex, 1);
                }
            } else {
                $(event.target).parent().find('.chkbx').removeClass('text-success').addClass('text-danger').text('In-Progress');
                if (!uncheckedTopics.includes(topicId)) {
                    uncheckedTopics.push(topicId);
                }
                const checkedIndex = checkedTopics.indexOf(topicId);
                if (checkedIndex > -1) {
                    checkedTopics.splice(checkedIndex, 1);  
                }
            }
        }
        
        function redirectToPush(){
            // console.log("Checked - " + checkedTopics);
            // console.log("Unchecked - " + uncheckedTopics);
            let course_id = (event.target.getAttribute('course_id'));
            if (checkedTopics.length > 0 || uncheckedTopics.length > 0) {
                const checkedTopicsString = checkedTopics.join(',');
                const uncheckedTopicsString = uncheckedTopics.join(',');
                $.ajax({
                    url: "../controller/TopicsStatus.php",
                    data: {
                        course_id: course_id,
                        student_id: student_id,
                        checkedTopics: checkedTopicsString,
                        uncheckedTopics: uncheckedTopicsString
                    },     
                    method: "GET",
                    success: function(success){
                        let response = JSON.parse(success);
                        let courseElement = document.querySelector('[c_status]');
                        if(response.status == 'success'){
                            if(response.courseStatus == true){
                                console.log(response.courseStatus);
                                courseElement.classList.remove('text-danger');
                                courseElement.classList.add('text-success');
                                courseElement.textContent = 'Completed';
                            } else{
                                console.log(response.courseStatus);
                                courseElement.classList.remove('text-success');
                                courseElement.classList.add('text-danger');
                                courseElement.textContent = 'In Progress';
                            }
                        }
                    },
                    error: function(error){
                            let response = JSON.parse(error); 
                            alert(response.message); 
                        }
                })
            } else {
                alert('No topics selected!');
            }
        }
</script>
</head>
<body>
<div class="container mt-3">
    <?php include './header.php' ?>
</div>
<div class="container">
    <div class="row text-center mt-5">
        <div class="col">
            <h1>Student Details</h1>
        </div>
    </div>
    <div class="row text-center mt-5">
        <div class="col">
            <img src="../uploads/<?= $student_details['file'] ?>" alt="Student Image" 
            style="width:150px; height:150px; border-radius:50%;">
        </div>
    </div>
    <div class="row text-center">
        <div class="col">
            <h5><strong>Name:</strong> <?= $student_details['first_name'] . ' ' . $student_details['last_name'] ?></h5>
        </div>
        <div class="col">
            <h5 class="ms-5"><strong class="ms-3">Email:</strong> <?= $student_details['email'] ?></h5>
        </div>
    </div>
    <div class="row text-center mt-3">
        <div class="col">
            <h5 class="ms-3"><strong>Phone:</strong> <?= $student_details['phone'] ?></h5>
        </div>
        <div class="col">
            <h5><strong>Date of Birth:</strong> <?= $student_details['dob'] ?></h5>
        </div>
    </div>
    <div class="accordion mt-5" id="accordionExample">
        <?php foreach ($courses as $course): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?= $course['course_name'] ?>">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $course['course_name'] ?>" aria-expanded="true" aria-controls="collapse-<?= $course['course_name'] ?>">
                        Course: <?= $course['course_name'] ?>
                    </button>
                </h2>
                <div id="collapse-<?= $course['course_name'] ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $course['course_name'] ?>" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>Status - <span class="<?= ($course['status'] == 'Completed') ? 'text-success' : 'text-danger' ?>" c_status><?= $course['status'] ?></span></p>
                        <h5>Topics:</h5>
                        <ul>
                            <?php foreach ($course['topics'] as $topic): ?>
                                <div class="d-flex justify-content-between">
                                    <li ><?= $topic['topic_name'] ?> - <span class="<?= ($topic['is_completed']=='Completed') ? 'text-success' : 'text-danger' ?> chkbx"><?= $topic['is_completed'] ?></span>
</li>
                                    <input type="checkbox" class="topic_checkbox" topic_id="<?= $topic['topic_id'] ?>" <?= ($topic['is_completed'] == 'Completed') ? 'checked' : '' ?> onclick="handleCheck(event)">
                                </div>
                            <?php endforeach; ?>
                            <button class="btn btn-primary mt-3" course_id="<?= $course['course_id'] ?>" onclick="redirectToPush()">Update Status</button>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>