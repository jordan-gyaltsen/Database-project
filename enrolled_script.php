<?php

session_start();
include ("helper.php");
if(isset($_POST['join'])) {
    include ("database_connect.php");
    $meeting_id = $_POST['meeting_id2'];
    $student_id = $_POST['student_id2'];
    $date = $_POST['date2'];

    $dayCheck = date('Y-m-d', strtotime('previous Thursday', strtotime( $date)));


    $enroll_query = "SELECT meeting_id FROM enroll WHERE meeting_id=$meeting_id AND student_id =$student_id;";
    $enroll_result = mysqli_query($db, $enroll_query); 
    $is_dupe = false;
    while ($t = $enroll_result->fetch_assoc()) {
        $is_dupe = true;
    }

    $meeting_query = "SELECT group_id,capacity FROM meetings WHERE meeting_id=$meeting_id;";
    $meeting_result = mysqli_query($db, $meeting_query); 

    $usergrade_query = "SELECT group_id FROM member_of
    WHERE student_id = $student_id;";
    $usergrade_result = mysqli_query($db, $usergrade_query); 

    $grp_id = [];
    while ($grow = $usergrade_result->fetch_assoc()) {
        array_push($grp_id, $grow['group_id']);
    }

    $meeting_query = "SELECT group_id,capacity FROM meetings WHERE meeting_id=$meeting_id;";
    $meeting_result = mysqli_query($db, $meeting_query); 

    while ($row = $meeting_result->fetch_assoc()) {

 
    if(date('Y-m-d') <= $dayCheck && in_array($row['group_id'],$grp_id) && $row['capacity'] < 6) {

       $sql = "INSERT IGNORE INTO enroll (meeting_id, student_id)
       VALUES ('$meeting_id', '$student_id')";
       
       $db->query($sql);

       if(!$is_dupe) {
       $next_capacity = $row['capacity'] + 1;

       $sql = "UPDATE meetings SET
       capacity = '$next_capacity'
       WHERE meeting_id = '$meeting_id'";
       
       if ($db->query($sql) === FALSE) {
         echo "Error: " . $sql . "<br>" . $db->error;
         exit();
       }
    }

       header("Location: enrolled.php?user_id= $student_id&user_type=student");
    } else {
        echo "You either chose the wrong group id or joined too late or capacity is full";
    }

}
} else if(isset($_POST['joinall'])) {
    include ("database_connect.php");
    $meeting_id = $_POST['meeting_id'];
    $student_id = $_POST['student_id2'];
    $date = $_POST['date'];

    foreach ($meeting_id as $key => $value) {

        $usergrade_query = "SELECT group_id FROM member_of
        WHERE student_id = $student_id;";
        $usergrade_result = mysqli_query($db, $usergrade_query); 
    
        $grp_id = [];
        while ($grow = $usergrade_result->fetch_assoc()) {
            array_push($grp_id, $grow['group_id'] );
        }
    
        $enroll_query = "SELECT meeting_id FROM enroll WHERE meeting_id=$meeting_id[$key] AND student_id =$student_id;";
        $enroll_result = mysqli_query($db, $enroll_query); 
        $is_dupe = false;
        while ($t = $enroll_result->fetch_assoc()) {
            $is_dupe = true;
        }
    
        $meeting_query = "SELECT group_id,capacity FROM meetings WHERE meeting_id=$meeting_id[$key];";
        $meeting_result = mysqli_query($db, $meeting_query); 
        $grow = mysqli_fetch_assoc($meeting_result);
        

        $dayCheck = date('Y-m-d', strtotime('previous Thursday', strtotime( $date[$key])));
        if(date('Y-m-d') <= $dayCheck && in_array($grow['group_id'],$grp_id) && $grow['capacity'] < 6) {
  
       

        $sql = "INSERT IGNORE INTO enroll (meeting_id, student_id)
        VALUES  ('$meeting_id[$key]', '$student_id')";

        $db->query($sql);
        if(!$is_dupe) {
        $next_capacity = $grow['capacity'] + 1;

        $sql = "UPDATE meetings SET
        capacity = '$next_capacity'
        WHERE meeting_id = '$meeting_id[$key]'";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }
    }
    }
    }
    header("Location: enrolled.php?user_id= $student_id&user_type=student");

} else if(isset($_POST['delete'])) {
    include ("database_connect.php");
    $meeting_id = $_POST['meeting_id2'];
    $student_id = $_POST['student_id2'];
    $sql = "DELETE FROM enroll 
    WHERE  meeting_id = '$meeting_id' AND  student_id = '$student_id'";


    $meeting_query = "SELECT capacity FROM meetings WHERE meeting_id=$meeting_id;";
    $meeting_result = mysqli_query($db, $meeting_query); 
    $grow = mysqli_fetch_assoc($meeting_result);

    $db->query($sql);
 
    if($grow['capacity'] > 0) {

    $next_capacity = $grow['capacity'] - 1;

    $sql = "UPDATE meetings SET
    capacity = '$next_capacity'
    WHERE meeting_id = '$meeting_id'";
    
    if ($db->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $db->error;
      exit();
    }
    }
    header("Location: enrolled.php?user_id= $student_id&user_type=student");
}
    ?>