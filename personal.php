<?php  
//check to see that userID properly set
//by default getting an error saying user does not exist. Commented out to see errors.
require 'header.php';
if(!isset($_GET['user_id'])) {
    echo "This user does not exist";
    exit();
}

$personal_userid = htmlspecialchars($_GET["user_id"]);
$personal_usertype = htmlspecialchars($_GET["user_type"]);

//Is the current user that is being edited a student or parent
if($personal_usertype == "student")  {
    $userpersonal_query = "SELECT name,bday,pronouns,allergies FROM users
                        INNER JOIN students ON student_id = id
                        WHERE `id`='$personal_userid';";
    $userpersonal_result = mysqli_query($db, $userpersonal_query); 

// if user is parent, query there name
} else if ($personal_usertype == "parent") {
    $userpersonal_query = "SELECT name FROM users 
    WHERE `id`='$personal_userid';";
    $userpersonal_result = mysqli_query($db, $userpersonal_query); 
}

//If user is a parent, query for all their children
if($user_type == "parent") {
    $c_query = "SELECT name,id FROM users
    INNER JOIN students as b ON id = b.student_id
    INNER JOIN child_of as a ON a.student_id = b.student_id
    WHERE `parent_id`='$user_id';";
    
    $c_result = mysqli_query($db, $c_query); 
}


$personal_name = "";
$personal_bday = "";
$personal_pronouns = "";
$personal_allergies = "";

while ($row = $userpersonal_result->fetch_assoc()) {
    $personal_name = $row['name'];
    if($personal_usertype == "student") {
        $personal_bday = $row['bday'];
        $personal_pronouns = $row['pronouns'];
        $personal_allergies = $row['allergies'];
    }
}
?>

</br>

<div>
<?php if($personal_usertype == "student") { ?>
    <form action="personal_script.php" method="post">
        <table>
            <tr>
                <td><?php echo $personal_name; ?></td>
            </tr>
            <tr>
                <td>Birthday</td>
                <td><input value="<?php echo $personal_bday; ?>" type="date" name="bday" id="bday" placeholder="" required /></td>
            </tr>
            <tr>
                <td>Pronouns</td>
                <td>
                    <input value="<?php echo $personal_pronouns; ?>" type="text" name="pronouns"
                        placeholder="Fill in your desired pronouns" id="student pronouns">
                </td>
            </tr>
            <tr>
                <td>Allergies</td>
                <td>
                    <input value="<?php echo $personal_allergies; ?>" type="text" name="allergies"
                        placeholder="Fill in any allergies you may have" id="student allergies">
                </td>
            </tr>

        </table>
        <input value="<?php echo $personal_usertype; ?>" name="t" type="hidden">
        <input value="<?php echo $personal_userid; ?>" name="i" type="hidden">
        <input type="submit" value="Update" name="submit" />
    </form>
    <?php } ?>

    <?php if($user_type == "parent") { ?>
        <p>My Children</p>
        <?php  
            while ($row = $c_result->fetch_assoc()) { 
        ?>
        <a href="personal.php?user_id=<?php echo $row['id']; ?>&user_type=student"><?php echo $row['name']; ?></a><br>
        <?php 
        } 
    } ?>
</div>
<?php
require 'footer.php';
?>