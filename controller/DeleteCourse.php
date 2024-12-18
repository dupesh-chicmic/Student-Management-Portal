<?php
include 'Database.php';
class DeleteCourse {
    public $con;
    public function __construct() {
        $this->con =  Database::connectDB(); 
    }

    function delete(){
        $sql = "DELETE FROM courses WHERE id='$_GET[id]'";
        $result = $this->con->query($sql);
        if($result === TRUE){
            header('location: ../view/CourseView.php');
        }
    }
}

$deleteuser = new DeleteCourse;
try{
$deleteuser->delete();
} catch(Exception $th){
    print_r($th);
    die;
}