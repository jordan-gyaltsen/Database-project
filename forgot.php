<html>
	<head>
		<title>Greendale Highschool Online Portal</title>
	</head>

	<body>
		<h1>Greendale Highschool Online Portal</h1>
		<h2>Student Parent forgot password</h2>

			<form action="forgot_script.php" method="post">
				<div id="labels">
					<label for="username">Username:</label><br>
					<input type="text" id="username" name="username"><br>
					</select><br>
					<label for="security_question">Security Question:</label><br>
					<select name="security_question" id="question_drop_down_menu" >
						<option value="sec_q_01">What is the name of the first person you kissed?</option>
						<option value="sec_q_02">What was the name of your elementary school?</option>
						<option value="sec_q_03">In what city or town does your nearest sibling live?</option>
						<option value="sec_q_04">What is your oldest cousin's first and last name?</option>
					</select>
					<br><br>
					<label for="security_answer">Security Answer:</label><br>
					<input type="text" id="security_answer" name="security_answer"><br><br>
				</div>

				<div id="buttons">
					<input type="submit" value="Submit"/>
					<button onclick="location.href='http://localhost/login.html'" type="button">Back</button>
				</div>
			</form>
	</body>
</html>