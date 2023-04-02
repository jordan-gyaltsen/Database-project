<?php
session_start();
include ("helper.php");
if(isset($_POST['create'])) {
    include ("database_connect.php");
    $next_meetingid =  getNextId("meetings","meeting_id",$db);
    $meeting_name = $_POST['meeting_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $group_id = $_POST['group_id'];
    $announcement = $_POST['announcement'];

    if (!isWeekend($date)) {
      echo "Only weekends";
      exit();
    }

    $mysqlDate=date("Y-m-d",strtotime($date));

    $mysqlDateInt=strtotime($mysqlDate);
    $day = date('D', $mysqlDateInt);
    
    $next_timeslot =  getNextId("time_slot","time_slot_id",$db);
    
    $mysqlTime = date('H:i', strtotime($time));
    $mysqlTime2 = date('H:i', strtotime($time) + 3600);



    //Create time slot 

    $sql = "INSERT INTO time_slot (time_slot_id, day_of_the_week,start_time,end_time)
    VALUES ('$next_timeslot', '$day', '$mysqlTime', '$mysqlTime2')";
    
    if ($db->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $db->error;
      exit();
    }

    //create meeting
        $sql = "INSERT INTO meetings (meeting_id, meeting_name,date,time_slot_id,capacity,group_id,announcement)
        VALUES ('$next_meetingid', '$meeting_name', '$mysqlDate', '$next_timeslot', 0 ,'$group_id', '$announcement')";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }
    
    header("Location: meeting.php");
} else  if(isset($_POST['update'])) {
  
    include ("database_connect.php");
    $meeting_name = $_POST['meeting_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $group_id = $_POST['group_id'];
    $announcement = $_POST['announcement'];
    $meeting_id = $_POST['meeting_id'];
    $time_slot_id = $_POST['time_slot_id'];
  

    foreach ($meeting_id as $key => $value) {
      if (!isWeekend($date[$key])) {
        echo "One of your meetings are not on the weekend";
        exit();
      }
      $mysqlDate=date("Y-m-d",strtotime($date[$key]));

      $mysqlDateInt=strtotime($mysqlDate);
      $day = date('D', $mysqlDateInt);
      
      $mysqlTime = date('H:i', strtotime($time[$key]));
      $mysqlTime2 = date('H:i', strtotime($time[$key]) + 3600);

   
    //update meeting
        $sql = "UPDATE meetings SET
        meeting_name = '$meeting_name[$key]',
        date = '$mysqlDate',
        group_id = '$group_id[$key]',
        announcement = '$announcement[$key]' 
        WHERE meeting_id = '$meeting_id[$key]'";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }
    
  
          $sql = "UPDATE time_slot SET
          day_of_the_week = '$day',
          start_time = '$time[$key]',
          end_time = '$mysqlTime2'
          WHERE time_slot_id = '$time_slot_id[$key]'";
          
          if ($db->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $db->error;
            exit();
          }

        }
    header("Location: meeting.php");
} else {
    echo "You can not come here without using a form.";
}
?>