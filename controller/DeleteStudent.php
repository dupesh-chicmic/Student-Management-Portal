<?php
include 'Database.php';
class DeleteStudent {
    public $con;
    public function __construct() {
        $this->con =  Database::connectDB(); 
    }

    function delete(){
        $sql = "DELETE FROM students WHERE id='$_GET[id]'";
        $result = $this->con->query($sql);
        if($result === TRUE){
            header('location: ../view/index.php');
        }
    }
}

$deleteuser = new DeleteStudent;
try{
$deleteuser->delete();
} catch(Exception $th){
    print_r($th);
    die;
}