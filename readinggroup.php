<?php  
//If admin allow the creation of groups
//Anyone can see group info (I guess just remove the check box and submit button from the table if not student)
//If student then you can join and leave groups
require 'header.php';

$group_query = "SELECT description,name,group_id,grade_req FROM groups;";
$group_result = mysqli_query($db, $group_query); 

$owned_query = "SELECT group_id FROM member_of 
                WHERE student_id = $user_id;";
                
$owned_result = mysqli_query($db, $owned_query); 

//get grade req.
if ($user_type == "student") { 
$grade_query = "SELECT grade FROM students WHERE student_id = $user_id;";
$graderesult = mysqli_query($db, $grade_query); 
$gradeinfo = $graderesult->fetch_assoc();
$grade = $gradeinfo['grade'];
 }
$belonged_groups = [];
while ($row = $owned_result->fetch_assoc()) { 
    array_push($belonged_groups,$row['group_id']);
} 
?>

</br>

<div>
    <?php 
if ($user_type == "admin") {  ?>
    <form action="readinggroup_script.php" method="post">
        <h2>Create new group</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">

                <th>Name</th>
                <th>Description</th>
                <th>Grade Req.</th>
            </tr>
            <tr bgcolor="#ffffff">
                <td><input type=" text" name="name" required></td>
                <td><input type=" text" name="desc" required></td>
                <td><input type=" number" pattern="[0-9]+" name="grade_req" required></td>
            </tr>
        </table>
        </br>

        <input type="submit" value="Insert" name="submit2" />
    </form>

    <?php } ?>
    <br><br><br>
    <form action="readinggroup_script.php" method="post">
        <h2>All Groups</h2>
        <table cellspacing="1" bgcolor="#000000">
            <tr bgcolor="#ffffff">
                <?php 
        
        if ($user_type == "student") { ?> <th>Your Groups</th><?php }?>
                <th>Name</th>
                <th>Description</th>
                <th>Grade Req.</th>
            </tr>

            <?php 
                while ($row = $group_result->fetch_assoc()) { ?>
            <tr bgcolor="#ffffff">
                <?php 
        
        if ($user_type == "student") { 
            ?>

                <td>
                    <?php if($grade >= $row['grade_req']) { ?>
                    <input name="group[]" type="checkbox"
                        <?php if(in_array($row['group_id'],$belonged_groups)) {echo "checked";} ?>
                        value="<?php echo $row['group_id']; ?>">
                    <?php } else {?> N/A <?php }?>
                </td>
                <?php }?>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['grade_req']; ?></td>
            </tr>
            <?php } ?>
        </table>
        </br>
        <?php 
        // Only students can update groups they are in
        if ($user_type == "student") { 
            
        ?>
        <input type="submit" value="Update" name="submit" />
        <?php 
        }
        ?>
    </form>
</div>

<?
require 'footer.php';
?>