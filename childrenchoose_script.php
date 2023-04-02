<?php
session_start();
if($_SESSION['user_type'] != "parent") {
    echo "This page is only for parents.";
    exit();
}
if(isset($_POST['submit'])) {
    include ("database_connect.php");
    
    $checkboxes = $_POST['children'];
    $parent_type = $_SESSION['user_type'];
    $parent_id = $_SESSION['user_id'];

    // Delete current children before adding new set
    $sql = "DELETE FROM child_of WHERE parent_id=$parent_id";

    if ($db->query($sql) === FALSE) {
        echo "Error deleting record: " . $db->error;
    }
    
    foreach ($checkboxes as $value) {
        $sql = "INSERT INTO child_of (student_id, parent_id)
        VALUES ('$value', '$parent_id')";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }
    } 
    header("Location: childrenchoose.php");
} else {
    echo "You can not come here without using a form.";
}
?>