<?php
session_start();
include ("helper.php");
if(isset($_POST['create'])) {
    include ("database_connect.php");
    $next_materialid =  getNextId("material","material_id",$db);
    $title = $_POST['title'];
    $author = $_POST['author'];
    $type = $_POST['type'];
    $url = $_POST['url'];
    $notes = $_POST['notes'];
    $date = $_POST['date'];
    $meeting_id = $_POST['meeting_id'];
    $isbn = $_POST['isbn'];

    $mysqlDate=date("Y-m-d",strtotime($date));

    $mysqlDateInt=strtotime($mysqlDate);
    $day = date('D', $mysqlDateInt);
    
        $sql = "INSERT INTO material
        (material_id,
        title,
        author,
        type,
        url,
        notes,
        assigned_date,
        meeting_id,
        isbn)

        VALUES ('$next_materialid', 
        '$title', 
        '$author', 
        '$type',
         '$url' ,
        '$notes',
        '$date',
        '$meeting_id',
        '$isbn')";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }
    
    header("Location: studymaterial.php");

} else if(isset($_POST['update'])) {
    include ("database_connect.php");
    $title = $_POST['title'];
    $author = $_POST['author'];
    $type = $_POST['type'];
    $url = $_POST['url'];
    $notes = $_POST['notes'];
    $material_id = $_POST['material_id'];
    $assigned_date = $_POST['date'];
    $meeting_id = $_POST['meeting_id'];
    $isbn = $_POST['isbn'];

    foreach ($meeting_id as $key => $value) {

      $mysqlDate=date("Y-m-d",strtotime($assigned_date[$key]));

    //update material
        $sql = "UPDATE material SET
        title = '$title[$key]',
        author = '$author[$key]',
        type = '$type[$key]',
        url = '$url[$key]' ,
        notes = '$notes[$key]' ,
        assigned_date = '$mysqlDate' ,
        meeting_id = '$meeting_id[$key]' ,
        isbn = '$isbn[$key]' 
        WHERE material_id = '$material_id[$key]'";
        
        if ($db->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $db->error;
          exit();
        }

        }
    
    header("Location: studymaterial.php");

}
    ?>