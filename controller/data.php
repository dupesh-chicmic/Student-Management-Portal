<?php
$table = 'students';

$primaryKey = 'id';

$columns = array(
    array( 
        'db' => 'file',
        'dt' => 0,
        'formatter' => function($d) {
            $imagePath = '../uploads/' . $d; 
            if (file_exists($imagePath)) {
                return '<img src="' . $imagePath . '" alt="Student Image" width="50" height="50" style="border-radius: 50%"/>';
            } else {
                return 'No Image'; 
            }
        }
    ),
    array( 'db' => 'first_name', 'dt' => 1 ),
    array( 'db' => 'last_name', 'dt' => 2 ),
    array( 'db' => 'email', 'dt' => 3 ),
    array( 'db' => 'phone', 'dt' => 4 ),
    array( 'db' => 'dob', 'dt' => 5 ),
    array(
        'db' => 'id', 
        'dt' => 6,
        'formatter' => function($id) {
            return '
            <a href="../view/StudentDetailView.php?id=' . $id . '"><button class="btn btn-info btn-sm">View</button></a>
            <a href="../view/UpdateStudentView.php?id=' . $id . '"><button class="btn btn-warning btn-sm">Edit</button></a>
            <button class="btn btn-danger btn-sm" onclick="if(confirm(\'Are you sure you want to delete this student?\')) window.location.href=\'../controller/DeleteStudent.php?id=' . $id . '\'">Delete</button>';
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
