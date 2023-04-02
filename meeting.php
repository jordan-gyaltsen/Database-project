<?php  
require 'header.php';

$meeting_query = "SELECT meeting_id,meeting_name,group_id,date_format(start_time,'%H:%i') as start_time,a.time_slot_id,date,capacity,announcement FROM meetings a
INNER JOIN time_slot b ON a.time_slot_id = b.time_slot_id;";
$meeting_result = mysqli_query($db, $meeting_query); 


$group_query = "SELECT description,name,group_id,grade_req FROM groups;";
$group_result = mysqli_query($db, $group_query); 
?>

</br>

<div>

    <form action="meeting_script.php" method="post">
        <h2>Create new meeting</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">

                <th>Name</th>
                <th>Date</th>
                <th>Start time</th>
                <th>Group</th>
                <th>Announcment</th>

            </tr>
            <tr bgcolor="#ffffff">
                <td><input type="text" name="meeting_name" required></td>
                <td><input type="date" type="text" name="date" required></td>
                <td><input type="time" name="time" min="00:00:00" max="23:59:59" required></td>
                <td><select name="group_id" required> <?php  while ($row = $group_result->fetch_assoc()) { ?> <option
                            value="<?php echo $row['group_id']; ?>"> <?php echo $row['name']; ?>
                        </option> <?php } ?> </select></td>
                <td><input type="text" name="announcement"></td>
            </tr>
        </table>
        </br>

        <input type="submit" value="Create" name="create" />
    </form>
    <br><br><br>
    <?php mysqli_data_seek($group_result,0); ?>
    <form action="meeting_script.php" method="post">
        <h2>All Meetings</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">

                <th>Name</th>
                <th>Date</th>
                <th>Start time</th>
                <th>Group</th>
                <th>Announcment</th>

            </tr>

            <?php 
                while ($row = $meeting_result->fetch_assoc()) { ?>
            <tr bgcolor="#ffffff">

                <td> <input value="<?php echo $row['meeting_id']; ?>" name="meeting_id[]" type="hidden">
                    <input value="<?php echo $row['time_slot_id']; ?>" name="time_slot_id[]" type="hidden">
                    <input value="<?php echo $row['meeting_name']; ?>" type="text" name="meeting_name[]" required>
                </td>
                <td><input value="<?php echo $row['date']; ?>" type="date" type="text" name="date[]" required></td>
                <td><input value="<?php echo $row['start_time']; ?>" type="time" name="time[]" min="00:00" max="23:59"
                        required>
                </td>
                <td><select name="group_id[]" required> <?php  while ($row2 = $group_result->fetch_assoc()) { ?> <option
                            <?php  if($row['group_id'] == $row2['group_id']) { echo "selected"; } ?>
                            value="<?php echo $row2['group_id']; ?>"> <?php echo $row2['name']; ?>
                        </option> <?php  } ?> </select></td>
                <td><input value="<?php echo $row['announcement']; ?>" type="text" name="announcement[]"></td>

            </tr>
            <?php  mysqli_data_seek($group_result,0); }?>
        </table>

        </br>

        <input type="submit" value="Update" name="update" />

    </form>
</div>

<?
require 'footer.php';
?>