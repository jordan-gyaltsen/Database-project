<?php  
require 'header.php';

$material_query = "SELECT isbn,material_id,meeting_id,title,author,type,assigned_date,url,notes FROM material;";
$material_result = mysqli_query($db, $material_query); 


$meeting_query = "SELECT meeting_name,meeting_id FROM meetings;";
$meeting_result = mysqli_query($db, $meeting_query); 
?>

</br>

<div>

    <form action="studymaterial_script.php" method="post">
        <h2>Create new Study Material</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">

                <th>Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>URL</th>
                <th>Notes</th>
                <th>Assigned Date</th>
                <th>Meeting</th>
                <th>Isbn</th>

            </tr>
            <tr bgcolor="#ffffff">
                <td><input type="text" name="title" required></td>
                <td><input type="text" name="author"></td>
                <td><input type="text" name="type"></td>
                <td><input type="text" name="url"></td>
                <td><input type="text" name="notes"></td>
                <td><input type="date" type="text" name="date" required></td>
                <td><select name="meeting_id" required> <?php  while ($row = $meeting_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['meeting_id']; ?>"> <?php echo $row['meeting_name']; ?>
                        </option> <?php } ?>
                    </select></td>
                <td><input type="text" name="isbn"></td>
            </tr>
        </table>
        </br>

        <input type="submit" value="Create" name="create" />
    </form>
    <br><br><br>
    <?php mysqli_data_seek($meeting_result,0); ?>

    <form action="studymaterial_script.php" method="post">
        <h2>Update Study Material</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">

                <th>Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>URL</th>
                <th>Notes</th>
                <th>Assigned Date</th>
                <th>Meeting</th>
                <th>Isbn</th>

            </tr>
            <?php 
                while ($row = $material_result->fetch_assoc()) { ?>
            <tr bgcolor="#ffffff">
                <td><input value="<?php echo $row['material_id']; ?>" name="material_id[]" type="hidden">
                    <input value="<?php echo $row['title']; ?>" type="text" name="title[]" required>
                </td>
                <td><input value="<?php echo $row['author']; ?>" type="text" name="author[]"></td>
                <td><input value="<?php echo $row['type']; ?>" type="text" name="type[]"></td>
                <td><input value="<?php echo $row['url']; ?>" type="text" name="url[]"></td>
                <td><input value="<?php echo $row['notes']; ?>" type="text" name="notes[]"></td>
                <td><input value="<?php echo $row['assigned_date']; ?>" type="date" type="text" name="date[]" required>
                </td>
                <td><select name="meeting_id[]" required> <?php  while ($row2 = $meeting_result->fetch_assoc()) { ?>
                        <option <?php  if($row['meeting_id'] == $row2['meeting_id']) { echo "selected"; } ?>
                            value="<?php echo $row2['meeting_id']; ?>"> <?php echo $row2['meeting_name']; ?>
                        </option> <?php } ?>
                    </select></td>
                <td><input value="<?php echo $row['isbn']; ?>" type="text" name="isbn[]"></td>
            </tr>
            <?php  mysqli_data_seek($meeting_result,0); }?>
        </table>
        </br>

        <input type="submit" value="Update" name="update" />
    </form>