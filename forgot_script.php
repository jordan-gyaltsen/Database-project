<?php
include ("database_connect.php");
session_start(); //start the session
	$username = $_POST['username'];
	$security_question = $_POST['security_question'];
	$security_answer = $_POST['security_answer'];

	$validation_query = "SELECT id FROM users WHERE email='$username' AND security_question='$security_question' AND security_answer='$security_answer';";
	/*
	$validation_stmt = $db->prepare($validation_query);
	$validation_stmt->execute();
	$validation_stmt->store_result();
	*/
	$validation_result = mysqli_query($db, $validation_query); //run query and store mysqli_result
	/*
	while($row = $validation_result->fetch_assoc())
	{
	$result_array=$row['id'];
	}
	*/
	if (mysqli_num_rows($validation_result) > 0) //if we get a nonzero number of rows back then the login info was found in the db
	{
		//login success
		$user_id = mysqli_fetch_column($validation_result, 0); //get the user's id
		echo "user id: $user_id";
		$_SESSION["user_id"] = $user_id; //save to session

		$is_admin_query = "SELECT COUNT(admin_id) FROM admins WHERE admin_id = $user_id;";
		$is_admin = mysqli_query($db, $is_admin_query);
		$row_admin = $is_admin->fetch_row();

		$is_parent_query = "SELECT COUNT(parent_id) FROM parents WHERE parent_id = $user_id;";
		$is_parent = mysqli_query($db, $is_parent_query);
		$row_parent = $is_parent->fetch_row();

		$is_student_query = "SELECT COUNT(student_id) FROM students WHERE student_id = $user_id;";
		$is_student = mysqli_query($db, $is_student_query);
		$row_student = $is_student->fetch_row();
		

		if ($row_admin[0] == 1) //is the user the admin?
		{
			$_SESSION["user_type"] = "admin"; //save to session
			header('Location: /enrolled.php');
		} elseif ($row_parent[0] == 1) //is the user a parent?
		{
			$_SESSION["user_type"] = "parent";
			header('Location: /enrolled.php');
		} elseif ($row_student[0] == 1) //surely the user is a student then... right?
		{
			$_SESSION["user_type"] = "student";
			header('Location: /enrolled.php');
		} else {
			//HALT AND CATCH FIRE
			echo ('<p> Error: user id found in user table but not found in any of the 3 user type tables </p>');
			exit;
		}
	} else //otherwise the login failed and we redirect them to the login page
	{
		header("Location: /login.html");
		exit;
	}

?>