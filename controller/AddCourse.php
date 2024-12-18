<?php
session_start();
include 'Database.php';

class AddCourse {
    public $con;
    public $error = [];
    
    public function __construct() {
        $this->con = Database::connectDB(); 
    }
    
    public function register() {
        $course_name = $_POST['course_name'] ?? '';
        $topics = $_POST['topics'] ?? [];

        if (empty(trim($course_name)) || !preg_match('/^[A-Za-z]+$/', $course_name)) {
            $this->error['empty_course_name'] = "Course name is required and should contain only letters and spaces..";
        }

        if (empty($topics)) {
            $this->error['empty_topics'] = "At least one topic is required.";
        } else {
            foreach ($topics as $index => $topic) {
                if (empty(trim($topic)) || !preg_match('/^[A-Za-z]+$/', $topic)) {
                    $this->error['empty_topic_' . $index] = "Topic " . ($index + 1) . " cannot be empty and should contain only letters.";
                }
            }
        }
        
        if (!empty($this->error)) {
            $_SESSION['error'] = $this->error;
            $_SESSION['form_data'] = $_POST;
            header('location: ../view/AddCourseView.php');
            exit;
        }

        $course_check_query = "SELECT * FROM courses WHERE course_name = '$course_name'";
        $result_check = $this->con->query($course_check_query);

        if ($result_check->num_rows > 0) {
            $this->error['course_exists'] = "This course already exists.";
            $_SESSION['error'] = $this->error;
            $_SESSION['form_data'] = $_POST;
            header('location: ../view/AddCourseView.php');
            exit;
        }

        $course_query = "INSERT INTO courses (course_name) VALUES ('$course_name')";
        $result_course = $this->con->query($course_query);

        $course_id = $this->con->insert_id;

        $topic_result  = [];
            
        foreach ($topics as $index => $topic) {
            $topic_query = "INSERT INTO topics (course_id, topic_name) VALUES ('$course_id', '$topic')";
            $this->con->query($topic_query);
        }

        unset($_SESSION['error'], $_SESSION['form_data']);
        header('location: ../view/CourseView.php');
        exit;
    }
}

if (isset($_POST['register'])) {
    $courseRegistration = new AddCourse();
    $courseRegistration->register();
}
?>
