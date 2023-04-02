<?php
session_start();
if(isset($_POST['submit'])) {
    include ("database_connect.php");
    
    $checkboxes = $_POST['group'];
    $student_id = $_SESSION['user_id'];

    // Delete current children before adding new set
    $sql = "DELETE FROM member_of WHERE student_id=$student_id";

    if ($db->query($sql) === FALSE) {
        echo "Error deleting record: " . $db->error;
    }
    
    foreach ($checkboxes as $value) {
        $sql = "INSERT INTO member_of (group_id, student_id)
        VALUES ('$value', '$student_id')";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }
    } 
    header("Location: readinggroup.php");
} else  if(isset($_POST['submit2'])) {
    include ("database_connect.php");
    //We could move this to a single file and make it a function for reuse
    //start
    $max_id_query = "SELECT MAX(`group_id`) FROM `groups`;";
    $max_id_result = mysqli_query($db, $max_id_query);
    $max_id_row = mysqli_fetch_row($max_id_result);
    $max_id = $max_id_row[0];
    $next_id = $max_id + 1;
    //end

    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $grade_req = $_POST['grade_req'];

    $sql = "INSERT INTO groups (group_id, name, description,grade_req)
    VALUES ('$next_id', '$name', '$desc', '$grade_req')";
    
    if ($db->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $db->error;
      exit();
    }
    header("Location: readinggroup.php");
} else {
    echo "You can not come here without using a form.";
}
?>