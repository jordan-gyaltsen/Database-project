<?php
session_start();
include ("database_connect.php");

$email = $_POST['email_text'];
$password_text = $_POST['password_text'];
$name_text = $_POST['name_text'];
$phone_number = $_POST['phone_number'];
$user_type = $_POST['user_type'];
$security_question = $_POST['security_question'];
$security_answer = $_POST['security_answer'];

if ($user_type == "student") {
	$grade_level = $_POST['grade_level'];
}

$max_id_query = "SELECT MAX(`id`) FROM `users`;";
$max_id_result = mysqli_query($db, $max_id_query);
$max_id_row = mysqli_fetch_row($max_id_result);
$max_id = $max_id_row[0];
$next_id = $max_id + 1;

$no_dupe_query = "SELECT COUNT(`id`) FROM `users` WHERE `email`='$email';";
$dupe_count_result = mysqli_query($db, $no_dupe_query); //need to check that no users already exist with the provided email
//echo("<p>dupe_count_result: $dupe_count_result\n</p>");
$dupe_count_row = mysqli_fetch_row($dupe_count_result);
$dupe_count = $dupe_count_row[0];


if (!$dupe_count > 0) //number of dupes is not greater than 0
{
	$insert_modification = "INSERT INTO `users` (`id`, `email`, `password`, `name`, `phone`, `security_question`, `security_answer`) VALUES ($next_id, '$email', PASSWORD('$password_text'), '$name_text', '$phone_number', '$security_question', '$security_answer');";
	#$insert_modification = "INSERT INTO `users` (`id`, `email`, `password`, `name`, `phone`, `security_question`, `security_answer`) VALUES ($next_id, '$email', PASSWORD('$password_text'), '$name_text', '$phone_number');";
	//insert the new user once we've determined no dupes exist
	$good_user_insert = mysqli_query($db, $insert_modification);

	if (!$good_user_insert) {
		//HALT AND CATCH FIRE
		echo ("<p>bad insert</p>");
		exit;
	}

	switch ($user_type) {
		case 'student':
			$insert_student_modification = "INSERT INTO students (student_id, grade) VALUES ($next_id, $grade_level)";
			mysqli_query($db, $insert_student_modification);
			//Make sure we actually made the student before we allow the user to go around the website validated
			$_SESSION['user_id'] = $next_id;
			$_SESSION['user_type']= $user_type;
			header('Location: /login_script.php');
			break;
		case 'parent':
			$insert_parent_modification = "INSERT INTO parents (parent_id) VALUES ($next_id)";
			mysqli_query($db, $insert_parent_modification);
			//Make sure we actually made the parent before we allow the parent to go around the website validated
			$_SESSION['user_id'] = $next_id;
			$_SESSION['user_type']= $user_type;
			header('Location: /childrenchoose.php');
			break;
		case 'admin':
			$insert_admin_modification = "INSERT INTO admins (admin_id) VALUES ($next_id)";
			mysqli_query($db, $insert_admin_modification);
			//Make sure we actually made the admin before we allow the parent to go around the website validated
			$_SESSION['user_id'] = $next_id;
			$_SESSION['user_type']= $user_type;
			header('Location: /enrolled.php');
			break;
		default:
			//HALT AND CATCH FIRE
			echo ('<p>Error: neither student, parent, or admin was selected in drop-down menu</p>');
	}
} else {
	//HALT AND CATCH FIRE
	echo ('<p>Error: an account using that email address already exists.</p>');
}
?>