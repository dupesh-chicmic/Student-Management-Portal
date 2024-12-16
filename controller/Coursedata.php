<?php
$table = 'courses';

$primaryKey = 'id';

$columns = array(
    array( 'db' => 'course_name', 'dt' => 0 ),
    array(
        'db' => 'id', 
        'dt' => 1,
        'formatter' => function($id) {
            return '
            <a href="../view/CourseDetailView.php?id=' . $id . '"><button class="btn btn-info btn-sm">View</button></a>
            <a href="../view/EditCourseView.php?id=' . $id . '"><button class="btn btn-warning btn-sm">Edit</button></a>
            <a href="../controller/DeleteStudent.php?id=' . $id . '"><button class="btn btn-danger btn-sm" onclick="if(confirm(\'Are you sure you want to delete this course?\')) window.location.href=\'../controller/DeleteCourse.php?id=' . $id . '\'">Delete</button></a>';
        }
    )
);
 
$sql_details = array(
    'user' => 'root',
    'pass' => 'password',
    'db'   => 'student_management',
    'host' => 'localhost'
);
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>