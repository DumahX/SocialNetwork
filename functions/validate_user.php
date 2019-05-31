<?php
	// validate user input, and assign to variables
	// check that the forms have been filled out, and reassign elements to variables
		foreach ($_POST as $key => $value) {
			$value = is_array($value) ? $value : trim($value);
			if (empty($value) && in_array($key, $required)) {
				$missing[] = $key;
				$$key = '';
			}
		}
		$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
		$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);	
		$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
		$compass = filter_var($_POST['compass'], FILTER_SANITIZE_STRING);

		// hash the password
		$hashpass = password_hash($password, PASSWORD_DEFAULT);

		// insert data into database
		$sql = "INSERT INTO users(first_name, last_name, username, email, password) VALUES ('$fname', '$lname', '$username', '$email', '$hashpass')";

		// make sure password matches new password
		if ($password != $compass) :
			$errors['passmatch'] = true;
		endif;

		// make sure password is at LEAST 6 characters
		if (strlen($password) < 6) :
			$errors['shortpass'] = true;
		endif;

		// make sure username doesn't already exist
		if ($username) {
			$checkUsers = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
			$getRows = mysqli_affected_rows($db);
			if ($getRows >= 1) {
				$errors['userexist'] = true;
			}
		}

		// make sure email doesn't already exist
		if ($email) {
			$checkEmails = mysqli_query($db, "SELECT * FROM users WHERE email='$email'");
			$getRows = mysqli_affected_rows($db);
			if ($getRows >= 1) {
				$errors['emailexist'] = true;
			}
		}

		if (!$errors && !$missing) :
			mysqli_query($db, $sql);
			$success = "<div alert='alert alert-success' role='alert'>
					<h4 class='alert-heading'>Thanks for signing up!</h4>
					<hr>
						<p>You will now be redirected to the login page</p>";
			header("Refresh: 5; URL='login.php'");
		endif;
?>