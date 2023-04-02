<?php
//Make query check for if student belongs to parent
include ("database_connect.php");

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "nice try";
    exit; //You're not supposed to be here
}
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
?>

<html>

<body>
    <p><?php echo "You are a $user_type"; ?><br> <a href="./logout.php">Logout</a></p>

    <div id=ribbon_menu>
        <a href='./enrolled.php?user_id=<?php echo $user_id; ?>&user_type=<?php echo $user_type; ?>'>Current
            Enrollments</a>

        <?php if($user_type == "admin") { ?>
        | <a href="./meeting.php">Meetings</a>
        <?php } ?> | <a href="./readinggroup.php">Reading Groups</a>
        <?php if($user_type == "admin") { ?>
        | <a href="./studymaterial.php">Study Material</a>
        <?php } ?>
        <?php if($user_type == "parent") { ?> | <a href="./childrenchoose.php"> | My Children</a>
        <?php } ?>
        <?php if($user_type == "student" || $user_type == "parent") { ?>
        | <a href='http://localhost/settings.php?user_id=<?php echo $user_id; ?>&user_type=<?php echo $user_type; ?>'>My
            Settings</a> <?php } ?>

        <?php if($user_type == "student" || $user_type == "parent") { ?>
        | <a href='http://localhost/personal.php?user_id=<?php echo $user_id; ?>&user_type=<?php echo $user_type; ?>'>My
            Personal</a> <?php } ?>

    </div>