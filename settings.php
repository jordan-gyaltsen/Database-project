<?php  
//Password talk to teammates how we want to do it
//QUERY AND GET THE CURRENT TYPE FROM THE URL VALUE
require 'header.php';
if(!isset($_GET['user_id'])) {
    echo "This user does not exist";
    exit();
}

$setting_userid = htmlspecialchars($_GET["user_id"]);
$setting_usertype = htmlspecialchars($_GET["user_type"]);

//Is the current user that is being edited a student or parent
if($setting_usertype == "student")  {
    $usersettings_query = "SELECT email,name,phone,grade FROM users
                        INNER JOIN students ON student_id = id
                        WHERE `id`='$setting_userid';";
    $usersettings_result = mysqli_query($db, $usersettings_query); 

} else if ($setting_usertype == "parent") {
    $usersettings_query = "SELECT email,name,phone FROM users
    WHERE `id`='$setting_userid';";
    $usersettings_result = mysqli_query($db, $usersettings_query); 
}

//Is the current logged in user a parent (Since only parents can see children)
if($user_type == "parent") {
    $c_query = "SELECT name,id FROM users
    INNER JOIN students as b ON id = b.student_id
    INNER JOIN child_of as a ON a.student_id = b.student_id
    WHERE `parent_id`='$user_id';";
    
    $c_result = mysqli_query($db, $c_query); 
}


$setting_name = "";
$setting_email = "";
$setting_phone = "";
$setting_gradelevel = "";

while ($row = $usersettings_result->fetch_assoc()) {
    $setting_name = $row['name'];
    $setting_email = $row['email'];
    $setting_phone = $row['phone'];
    if($setting_usertype == "student") {
        $setting_gradelevel = $row['grade'];
    }
}
?>

</br>
<div>
    <form action="settings_script.php" method="post">
        <table>
            <tr>
                <td>Email</td>
                <td><input readonly disabled value="<?php echo $setting_email; ?>" type="email" name="email_text"
                        id="user_email" /></td>
            </tr>
            <tr>
                <td>Full Name</td>
                <td><input value="<?php echo $setting_name; ?>" type=" text" name="name_text" id="user_real_name"
                        placeholder="" required /></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><input value="<?php echo $setting_phone; ?>" type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                        name="phone_number" id="user_phone_number" placeholder="" required /></td>
            </tr>
            <?php if($setting_usertype == "student") { ?>
            <tr>
                <td>Grade Level</td>
                <td>
                    <input value="<?php echo $setting_gradelevel; ?>" type=" text" name="grade_level"
                        placeholder="only required for students" id="student_grade_level">
                </td>
            </tr>

            <?php } ?>
        </table>
        <input value="<?php echo $setting_usertype; ?>" name="t" type="hidden">
        <input value="<?php echo $setting_userid; ?>" name="i" type="hidden">
        <input type="submit" value="Update" name="submit" />

    </form>
    <?php if($user_type == "parent") { ?>
    <p>My Children</p>
    <?php
        while ($row = $c_result->fetch_assoc()) {
    ?>

    <a href="settings.php?user_id=<?php echo $row['id']; ?>&user_type=student"><?php echo $row['name']; ?></a><br>

    <?php
        }
    }
    ?>
</div>

<?php
require 'footer.php';
?>