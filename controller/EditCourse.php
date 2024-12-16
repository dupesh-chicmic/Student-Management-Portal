<?php
include 'Database.php';
$con = Database::connectDB();

if (isset($_POST['updateCourse'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $topics = $_POST['topics'];
    $topic_ids = $_POST['topic_ids'];

    $sql_courseUpdate = "UPDATE courses SET course_name = '$course_name' WHERE id = '$course_id'";
    $result_courseUpdate = $con->query($sql_courseUpdate);

    foreach ($topics as $index => $topic_name) {
        $topic_id = $topic_ids[$index];
        $sql_topicUpdate = "UPDATE topics SET topic_name = '$topic_name' WHERE id = '$topic_id'";
        $result_topicUpdate = $con->query($sql_topicUpdate);
    }

        if ($result_courseUpdate && $result_topicUpdate) {
        header('Location: ../view/CourseDetailView.php?id=' . $course_id);
    } else {
        echo "Error updating course and topics.";
    }
}
?>
