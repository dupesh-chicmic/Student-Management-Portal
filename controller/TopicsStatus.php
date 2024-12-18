<?php
session_start();
include 'Database.php';

class UpdataStatus {
    public $con;
    
    public function __construct() {
        $this->con = Database::connectDB(); 
    }

    public function updateStatus(){
        $response = [];
        $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;
        $student_id = intval($student_id);

        $checkedUpdated = false; 
        $uncheckedUpdated = false;
        // [1=>true,3=>false]
        if (isset($_GET['checkedTopics'])) {
            $checkedTopics = explode(',', $_GET['checkedTopics']);
        
            foreach ($checkedTopics as $checked_topic_id) {
                $checked_topic_id = intval($checked_topic_id);
                $sql_check = "UPDATE students_topics SET is_completed = '1' WHERE topic_id = '$checked_topic_id' AND student_id = '$student_id'";
                $result = $this->con->query($sql_check);
                if ($result === TRUE) {
                    $checkedUpdated = true;
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to change topic status to complete.'
                    ];
                    echo json_encode($response);
                    return;
                }
            }
        }

        if (isset($_GET['uncheckedTopics'])) {
            $uncheckedTopics = explode(',', $_GET['uncheckedTopics']);
            
            foreach ($uncheckedTopics as $unchecked_topic_id) {
                $unchecked_topic_id = intval($unchecked_topic_id);
                $sql_uncheck = "UPDATE students_topics SET is_completed = '0' WHERE topic_id = '$unchecked_topic_id' AND student_id = '$student_id'";

                $result = $this->con->query($sql_uncheck);
                if ($result === TRUE) {
                    $uncheckedUpdated = true;
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to change topic status to incomplete.'
                    ];
                    echo json_encode($response);
                    return;
                }
            }
        }
        
            if ($checkedUpdated || $uncheckedUpdated) {
                $courseCompleted = $this->updateCourseStatus($student_id);
                $response = [
                    'status' => 'success',
                    'message' => 'Topics updated successfully.',
                    'courseStatus' => $courseCompleted
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'No topics were updated.',
                ];
            }
        
            echo json_encode($response);  
    }
    private function updateCourseStatus($student_id) {
        $course_id = $_GET['course_id'];
        // $sql_courses = "SELECT c.id AS course_id FROM students_courses sc INNER JOIN courses c ON sc.course_id = c.id WHERE sc.student_id = '$student_id'";
        // $result_courses = $this->con->query($sql_courses);
        
        // while ($course = $result_courses->fetch_assoc()) {
            // $course_id = $course['course_id'];
            $sql_check_all_topics = "SELECT COUNT(*) AS total_topics, SUM(is_completed) AS completed_topics FROM students_topics st INNER JOIN topics t ON st.topic_id = t.id WHERE st.student_id = '$student_id' AND t.course_id = '$course_id'";
            $result_check = $this->con->query($sql_check_all_topics);
            $row_check = $result_check->fetch_assoc();
            $total_topics = $row_check['total_topics'];
            $completed_topics = $row_check['completed_topics'];
            
            $courseCompleted = false;
            $new_course_status = null;

            if($completed_topics == $total_topics) { 
                
                $new_course_status =  1;
                $courseCompleted = true;
                    
            } else { 
                $new_course_status =  0;
                $courseCompleted = false;
            } 
            
            $sql_update_course_status = "UPDATE students_courses SET is_completed = '$new_course_status' WHERE student_id = '$student_id' AND course_id = '$course_id'";
            $this->con->query($sql_update_course_status);

            return $courseCompleted;
        // }
    }
}

$updateStatus = new UpdataStatus;
$updateStatus->updateStatus();
?>