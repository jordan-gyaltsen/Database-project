<?php  

require 'header.php';
$setting_userid = htmlspecialchars($_GET["user_id"]);
$setting_usertype = htmlspecialchars($_GET["user_type"]);

$meeting_query = "SELECT meeting_id,grade_req,meeting_name,a.group_id,date_format(start_time,'%H:%i') as start_time,date_format(end_time,'%H:%i') as end_time,end_time,a.time_slot_id,date,capacity,announcement FROM meetings a
INNER JOIN time_slot b ON a.time_slot_id = b.time_slot_id
INNER JOIN groups c ON c.group_id = a.group_id;";
$meeting_result = mysqli_query($db, $meeting_query); 

$enroll_query = "SELECT a.meeting_id,meeting_name,group_id,date_format(start_time,'%H:%i') as start_time,date_format(end_time,'%H:%i') as end_time,end_time,a.time_slot_id,date,capacity,announcement FROM meetings a
INNER JOIN time_slot b ON a.time_slot_id = b.time_slot_id
INNER JOIN enroll c ON c.meeting_id = a.meeting_id
WHERE c.student_id = $setting_userid;";
$enroll_result = mysqli_query($db, $enroll_query); 


$group_query = "SELECT description,name,group_id,grade_req FROM groups;";
$group_result = mysqli_query($db, $group_query); 


if($user_type == "parent") {
    $c_query = "SELECT name,id FROM users
    INNER JOIN students as b ON id = b.student_id
    INNER JOIN child_of as a ON a.student_id = b.student_id
    WHERE `parent_id`='$user_id';";
    
    $c_result = mysqli_query($db, $c_query); 
}

if($user_type == "admin") {
    $c_query = "SELECT name,id FROM users
    INNER JOIN students as b ON id = b.student_id;";
    
    $c_result = mysqli_query($db, $c_query); 
}


?>

</br>

<div>

    <form action="enrolled_script.php" method="post">
        <h2>Upcoming Meetings</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">
                <?php if($setting_usertype == "student") { ?>
                <th></th>
                <?php } ?>
                <th>Name</th>
                <th>Date</th>
                <th>Start time</th>
                <th>Group</th>
                <th>Announcment</th>
                <th>Capacity</th>
            </tr>

            <?php 
                while ($row = $meeting_result->fetch_assoc()) {
					$sqlDate = $row['date'];
					$sqlTime = $row['start_time'];


					$theDate = date('Y-m-d H:i', strtotime("$sqlDate $sqlTime"));
					if($theDate > date('Y-m-d H:i')  ) {
					?>
            <tr bgcolor="#ffffff">
                <?php if($setting_usertype == "student") { ?>
                <td>
                    <form action="enrolled_script.php" method="post">
                        <input type="submit" value="Join" name="join" />
                        <input value="<?php echo $row['meeting_id']; ?>" name="meeting_id2" type="hidden">
                        <input value="<?php echo $setting_userid; ?>" name="student_id2" type="hidden">
                        <input value="<?php echo $row['date']; ?>" name="date2" type="hidden">
                    </form>
                </td>
                <?php } ?>
                <td>
                    <input value="<?php echo $row['meeting_id']; ?>" name="meeting_id[]" type="hidden">
                    <input value="<?php echo $row['time_slot_id']; ?>" name="time_slot_id[]" type="hidden">
                    <?php echo $row['meeting_name']; ?>
                </td>
                <td><input value="<?php echo $row['date']; ?>" type="date" type="text" name="date[]" required></td>
                <td><input value="<?php echo $row['start_time']; ?>" type="time" name="time[]" min="00:00:00"
                        max="23:59:59" required>
                </td>
                <td><select name="group_id[]" required> <?php  while ($row2 = $group_result->fetch_assoc()) { ?> <option
                            <?php  if($row['group_id'] == $row2['group_id']) { echo "selected"; } ?>
                            value="<?php echo $row2['group_id']; ?>"> <?php echo $row2['name']; ?>
                        </option> <?php  } ?> </select></td>
                <td><?php echo $row['announcement']; ?></td>
                <td><?php echo $row['capacity']; ?></td>

            </tr>
            <?php  mysqli_data_seek($group_result,0); }}?>
        </table>

        </br>
        <?php if($setting_usertype == "student") { ?>
        <input type="submit" value="Join All in your group" name="joinall" />
        <?php } ?>
    </form>


    <h2>Past Meetings</h2>
    <table cellspacing="1" bgcolor="#000000">
        <tr bgcolor="#ffffff">

            <th>Name</th>
            <th>Date</th>
            <th>Start time</th>
            <th>Group</th>
            <th>Announcment</th>
        </tr>

        <?php 
		mysqli_data_seek($meeting_result,0);
                while ($row = $meeting_result->fetch_assoc()) {
					$sqlDate = $row['date'];
					$sqlTime = $row['start_time'];


					$theDate = date('Y-m-d H:i', strtotime("$sqlDate $sqlTime"));
					if($theDate < date('Y-m-d H:i')) {
					?>
        <tr bgcolor="#ffffff">

            <td>
                <?php echo $row['meeting_name']; ?>
            </td>
            <td><input value="<?php echo $row['date']; ?>" type="date" type="text" name="date[]" required></td>
            <td><input value="<?php echo $row['start_time']; ?>" type="time" name="time[]" min="00:00:00" max="23:59:59"
                    required>
            </td>
            <td><select name="group_id[]" required> <?php  while ($row2 = $group_result->fetch_assoc()) { ?> <option
                        <?php  if($row['group_id'] == $row2['group_id']) { echo "selected"; } ?>
                        value="<?php echo $row2['group_id']; ?>"> <?php echo $row2['name']; ?>
                    </option> <?php  } ?> </select></td>
            <td><?php echo $row['announcement']; ?></td>

        </tr>
        <?php  mysqli_data_seek($group_result,0); }}?>
    </table>


    <?php if ($setting_usertype == "student") { ?>
    <h2>Meetings you are enrolled in</h2>
    <table cellspacing="1" bgcolor="#000000">
        <tr bgcolor="#ffffff">

            <th>Name</th>
            <th>Date</th>
            <th>Start time</th>
            <th>Group</th>
            <th>Announcment</th>
            <th>Leave Meeting</th>

        </tr>

        <?php 
	
                while ($row = $enroll_result->fetch_assoc()) {
					$mm = $row['meeting_id'];
					$studentenroll_query = "SELECT name,email FROM users a
					INNER JOIN students b ON b.student_id = a.id
					INNER JOIN enroll c ON c.student_id = b.student_id
					WHERE c.meeting_id = $mm;";
					$studentenroll_result = mysqli_query($db, $studentenroll_query); 


                    $meetingmaterial_query = "SELECT title,author,type,notes,isbn FROM material 

					WHERE meeting_id = $mm;";
					$meetingmaterial_result = mysqli_query($db, $meetingmaterial_query); 
					
					?>
        <tr bgcolor="#ffffff">

            <td>
                <?php echo $row['meeting_name']; ?>
            <td><?php echo $row['date']; ?></td>
            <td><input value="<?php echo $row['start_time']; ?>" type="time" name="time[]" min="00:00:00" max="23:59:59"
                    required>
            </td>
            <td><select name="group_id[]" required> <?php  while ($row2 = $group_result->fetch_assoc()) { ?> <option
                        <?php  if($row['group_id'] == $row2['group_id']) { echo "selected"; } ?>
                        value="<?php echo $row2['group_id']; ?>"> <?php echo $row2['name']; ?>
                    </option> <?php  } ?> </select></td>
            <td><?php echo $row['announcement']; ?></td>
            <td>
                <form action="enrolled_script.php" method="post">
                    <input type="submit" value="Leave" name="delete" />
                    <input value="<?php echo $row['meeting_id']; ?>" name="meeting_id2" type="hidden">
                    <input value="<?php echo $setting_userid; ?>" name="student_id2" type="hidden">
                </form>
            </td>

        </tr>
        <?php  while ($row2 = $studentenroll_result->fetch_assoc()) {  ?>
        <tr bgcolor="#ffffff">
            <td>
                <p>STUDENT -></p>
            </td>
            <td>
                <p><?php echo $row2['name']; ?></p>
            </td>
            <td>
                <p><?php echo $row2['email']; ?></p>
            </td>

            <td>

            </td>
            <td>

            </td>
            <td>

            </td>
        </tr>
        <?php  } 
	
		?>

        <?php  while ($row2 = $meetingmaterial_result->fetch_assoc()) {  ?>
        <tr bgcolor="#ffffff">
            <td>
                <p>MATERIAL -></p>
            </td>
            <td>
                <p><?php echo $row2['title']; ?></p>
            </td>
            <td>
                <p><?php echo $row2['author']; ?></p>
            </td>
            <td>
                <p><?php echo $row2['type']; ?></p>
            </td>
            <td>
                <p><?php echo $row2['notes']; ?></p>
            </td>
            <td>
                <a
                    href="https://www.amazon.com/s?k=<?php echo $row2['isbn']; ?>&i=stripbooks&crid=2G398ITSOLPWK&sprefix=assa%2Cstripbooks%2C118&ref=nb_sb_noss_2"><?php echo $row2['isbn']; ?></a>
            </td>
        </tr>
        <?php  } ?>
        <?php  mysqli_data_seek($group_result,0); }?>
    </table>
    <?php	}?>

    <?php if($user_type == "parent" ||$user_type == "admin" ) { ?>
    <p>Students</p>
    <?php
        while ($row = $c_result->fetch_assoc()) {
    ?>

    <a href="enrolled.php?user_id=<?php echo $row['id']; ?>&user_type=student"><?php echo $row['name']; ?></a><br>

    <?php
        }
    }
    ?>
</div>

<?
require 'footer.php';
?>