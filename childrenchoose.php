<?php  
require 'header.php';

$children_query = "SELECT email,name,phone,grade,id FROM users
                   INNER JOIN students ON student_id = id;";
$children_result = mysqli_query($db, $children_query); 

$owned_query = "SELECT student_id FROM child_of as a
                INNER JOIN parents as b ON a.parent_id = b.parent_id
                WHERE b.parent_id = $user_id;";
                
$owned_result = mysqli_query($db, $owned_query); 

$children = [];
while ($row = $owned_result->fetch_assoc()) { 
    array_push($children,$row['student_id']);
} 
?>

</br>
<div>
    <form action="childrenchoose_script.php" method="post">
        All Students
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">
                <th>Your Children</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Grade level</th>
            </tr>

            <?php 
                while ($row = $children_result->fetch_assoc()) { ?>
            <tr bgcolor="#ffffff">
                <td><input name="children[]" type="checkbox"
                        <?php if(in_array($row['id'],$children)) {echo "checked";} ?> value="<?php echo $row['id']; ?>">
                </td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['grade']; ?></td>
            </tr>
            <?php } ?>
        </table>
        </br>
        <input type="submit" value="Update" name="submit" />

    </form>
</div>

<?
require 'footer.php';
?>