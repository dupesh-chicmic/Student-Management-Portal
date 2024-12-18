<?php
session_start();
include 'Database.php';

class UpdateStudent {
    public $con;
    public $error = [];

    public function __construct() {
        $this->con = Database::connectDB();
    }

    public function update() {
        $id = $_POST['id'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $imagePath = '';

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
        }

        if (empty($imagePath)) {
            $sql_image = "SELECT file FROM students WHERE id = '$id'";
            $result = $this->con->query($sql_image);
            if ($result && $row = $result->fetch_assoc()) {
                $imagePath = $row['file'];
            }
        }

        if (!empty($this->error)) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['error'] = $this->error;
            header('location: ../view/UpdateStudentView.php');
            exit;
        }

        $sql_update = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone', dob = '$dob', file = '$imagePath' WHERE id = $id";
        $result_update = $this->con->query($sql_update);
        if ($result_update === TRUE) {
            unset($_SESSION['error'], $_SESSION['form_data']); 
            header('location: ../view/index.php');
        } else {
            $this->error['database'] = "Database error: " . $this->con->error;
            header('location: ../view/UpdateStudentView.php');
            exit;
        }   
    }
}

if (isset($_POST['update'])) {
    $updateStudent = new UpdateStudent();
    $updateStudent->update();
}
?>