<?php
session_start();
include 'Database.php';

class EditCourse {
    public $con;
    public $error = [];
    
    public function __construct() {
        $this->con = Database::connectDB();
    }
    
    public function update() {
        $course_id = $_POST['course_id'] ?? '';
        $course_name = $_POST['course_name'] ?? '';
        $topics = $_POST['topics'] ?? [];
        $topic_ids = $_POST['topic_ids'] ?? [];

        if (empty(trim($course_name)) || !preg_match('/^[A-Za-z\s]+$/', $course_name)) {
            $this->error['empty_course_name'] = "Course name is required and should contain only letters and spaces.";
        }

        if (empty($topics)) {
            $this->error['empty_topics'] = "At least one topic is required.";
        } else {
            foreach ($topics as $index => $topic) {
                if (empty(trim($topic)) || !preg_match('/^[A-Za-z\s]+$/', $topic)) {
                    $this->error['empty_topic_' . $index] = "Topic " . ($index + 1) . " cannot be empty and should contain only letters.";
                }
            }
        }

        if (!empty($this->error)) {
            $_SESSION['error'] = $this->error;
            $_SESSION['form_data'] = $_POST;
            header('location: ../view/EditCourseView.php?id=' . $course_id);
            exit;
        }

        $sql_courseUpdate = "UPDATE courses SET course_name = '$course_name' WHERE id = '$course_id'";
        $result_courseUpdate = $this->con->query($sql_courseUpdate);

        $existing_topic_ids = array_column($this->con->query("SELECT id FROM topics WHERE course_id = '$course_id'")->fetch_all(MYSQLI_ASSOC), 'id');

        foreach ($topics as $index => $topic_name) {
            $topic_id = isset($topic_ids[$index]) ? $topic_ids[$index] : null;

            if ($topic_id) {
                $sql_topicUpdate = "UPDATE topics SET topic_name = '$topic_name' WHERE id = '$topic_id'";
                $this->con->query($sql_topicUpdate);
            
                $existing_topic_ids = array_diff($existing_topic_ids, [$topic_id]);
            } else {
                $sql_topicInsert = "INSERT INTO topics (topic_name, course_id) VALUES ('$topic_name', '$course_id')";
                $this->con->query($sql_topicInsert);
            }
        }

        foreach ($existing_topic_ids as $topic_id) {
            $sql_topicDelete = "DELETE FROM topics WHERE id = '$topic_id'";
            $this->con->query($sql_topicDelete);
        }

        unset($_SESSION['error']);
        unset($_SESSION['form_data']);
        header('Location: ../view/CourseDetailView.php?id=' . $course_id);
        exit;
    }
}

if (isset($_POST['updateCourse'])) {
    $courseUpdate = new EditCourse();
    $courseUpdate->update();
}
?>
