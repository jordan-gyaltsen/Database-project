<?php
// We need to do a query to check the user_type of the id passed here
// Not the user_type of the logged in user
if(isset($_POST['submit'])) {
    session_start();
    include ("database_connect.php");

    //These 2 below are the information of the user that is being edited not the current logged in user
    $utype = $_POST['t'];
    $user_id = $_POST['i'];
    //$email = $_POST['email_text'];
    // $password_text = $_POST['password_text'];
    $name_text = $_POST['name_text'];
    $phone_number = $_POST['phone_number'];
    

    $sql = "UPDATE users SET name='$name_text', phone='$phone_number' WHERE id='$user_id'";

    if (mysqli_query($db,$sql) === FALSE) {
        echo "Error updating record: " . $db->error;
    }

    //Only students have grade level. Since parent can also use this file we do not want them to run this
    if( $utype == "student") {
        $grade_level = $_POST['grade_level'];
        $sql = "UPDATE students SET grade='$grade_level' WHERE student_id='$user_id'";
        
        if (mysqli_query($db,$sql) === FALSE) {
            echo "Error updating record: " . $db->error;
        }
    }
    header("Location: settings.php?user_id=$user_id&user_type=$utype");
} else {
    echo "You can not come here without using a form.";
}
?>