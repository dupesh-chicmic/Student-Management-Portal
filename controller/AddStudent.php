<?php
session_start();
include 'Database.php';

class AddStudent {
    public $con;
    public $error = [];
    
    public function __construct() {
        $this->con = Database::connectDB(); 
    }
    
    public function register() {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $course = $_POST['course'] ?? [];

        if (!preg_match('/^[A-Za-z]+$/', $first_name)) {
            $this->error['invalid_first_name'] = "First Name is required and only alphabets are allowed.";
        }

        if (!preg_match('/^[A-Za-z]+$/', $last_name) && !empty(trim($last_name))) {
            $this->error['invalid_last_name'] = "Only alphabets are allowed.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error['invalid_email'] = "Invalid email format.";
        }

        if (!preg_match('/^\d{10}$/', $phone)) {
            $this->error['invalid_phone'] = "Invalid Phone number. Must be 10 digits.";
        }

        if (empty($dob)) {
            $this->error['invalid_dob'] = "Date of Birth is required.";
        }

        if (empty($course)) {
            $this->error['invalid_courses'] = "At least one course must be selectedd.";
        }
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $dir = '../uploads/';
            $tempName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFilename = time() . '_' . basename($fileName);
            $targetFile = $dir . $newFilename;
        
            $fileSize = $_FILES['image']['size'];
            $check = getimagesize($tempName);
    
            if ($check !== false) {
                if (!in_array($fileType, ["png", "jpg", "jpeg"])) {
                    $this->error['image'] = "File must be in PNG, JPG, or JPEG format.";
                }
                if ($fileSize > 1500000) {
                    $this->error['image'] = "File size must not exceed 1.5 MB.";
                }

                if (empty($this->error['image'])) {
                    if (move_uploaded_file($tempName, $targetFile)) {
                        $imagePath = $targetFile; 
                    } else {
                        $this->error['image'] = "File upload failed.";
                    }
                }
            } else {
                $this->error['image'] = "Uploaded file is not a valid image.";
            }
        } else {
            $this->error['image'] = "Image file is required.";
        }

        if (!empty($this->error)) {
            $_SESSION['error'] = $this->error;
            $_SESSION['form_data'] = $_POST;
            header('location: ../view/AddStudentView.php');
            exit;
        }

        $sql = "INSERT INTO students (first_name,last_name, email, phone, dob, file) VALUES ('$first_name','$last_name', '$email', '$phone', '$dob', '$imagePath')";
        $result = $this->con->query($sql);

        if ($result === TRUE) {
            $student_id = $this->con->insert_id;
            foreach ($course as $course_id) {
                $sql_course = "INSERT INTO students_courses (student_id, course_id) VALUES ('$student_id', '$course_id')";
                $this->con->query($sql_course);
                $sql_topics = "SELECT id FROM topics WHERE course_id = '$course_id'";
                $result_topics = $this->con->query($sql_topics);
                while ($row_topics = $result_topics->fetch_assoc()) {
                    $topic_id = $row_topics['id'];
                    $sql_topic = "INSERT INTO students_topics (student_id, topic_id) VALUES ('$student_id', '$topic_id')";
                    $this->con->query($sql_topic);
                }
            }
            unset($_SESSION['error'], $_SESSION['form_data']); 
            header('location: ../view/index.php');
            exit;
        } else {
            $this->error['database'] = "Database error: " . $this->con->error;
            header('location: ../view/AddStudentView.php');
            exit;
        }
    }
}

if (isset($_POST['register'])) {
    $registration = new AddStudent;
    $registration->register();
}
?>