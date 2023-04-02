<?php
// We need to do a query to check the user_type of the id passed here
// Not the user_type of the logged in user
if(isset($_POST['submit'])) {
    session_start();
    include ("database_connect.php");

    //These 2 below are the information of the user that is being edited not the current logged in user
    $utype = $_POST['t'];
    $user_id = $_POST['i'];

    //We only care about the personal information for students. Disable this feature if user is a parent.
    if( $utype == "student") {
        $bday = $_POST['bday'];
        $allergies = $_POST['allergies'];
        $pronouns = $_POST['pronouns'];
        $sql = "UPDATE students SET bday='$bday', allergies='$allergies', pronouns='$pronouns' WHERE student_id='$user_id'";
        
        if (mysqli_query($db,$sql) === FALSE) {
            echo "Error updating record: " . $db->error;
        }
    }
    header("Location: personal.php?user_id=$user_id&user_type=$utype");
} else {
    echo "You can not come here without using a form.";
}
?>