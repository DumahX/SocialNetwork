<?php
// initialize the session
session_start();

// check if user is logged in, if not then redirect to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	// throw the redirect
	header("location: login.php");
	exit;
}

// connect to database
require_once 'data/db_conn.php';

// get functions
require 'functions/functions.php';

// define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

	// validate new password
	if(empty($_POST['new_password'])) {
		$new_password_err = "Please enter the new password";
	} elseif(strlen($_POST['new_password']) < 6) {
		$new_password_err = "Pass must be at least 6 characters";
	} else {
		$new_password = trim($_POST['new_password']);
	}

	// validate confirm password
	if(empty($_POST['confirm_password'])) {
		$confirm_password_err = "Please confirm the password";
	} else {
		$confirm_password = trim($_POST['confirm_password']);
		if(empty($new_password_err) && ($new_password != $confirm_password)) {
			$confirm_password_err = "Password did not match";
		}
	}

	// check input errors before uploading to database
	if(empty($new_password_err) && empty($confirm_password_err)) {
		// prepare update statement
		$sql = "UPDATE users SET password = ? WHERE id = ?";

		if($stmt = mysqli_prepare($db, $sql)) {
			// bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

			// set parameters and hash new password
			$param_password = password_hash($new_password, PASSWORD_DEFAULT);
			$param_id = $_SESSION['id'];

			// attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)) {
				// password update successful, destroy session and redirec to login
				session_destroy();
				header("location: login.php");
				exit();
			} else {
				echo "Oops! Something went wrong, please try again later";
			}
		}
		// close statement
		mysqli_stmt_close($stmt);
	}
	// close connection
	mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Reset Password</title>
	<link rel="stylesheet" href="styles/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
	<style>
		body{ font: 14px sans-serif; }
		.wrapper{ width: 350px; padding: 20px; }
	</style>
</head>
<body>
	<div class="wrapper text-center">
		<h1>The Network</h1>
		<h2>Reset Password</h2>
		<p>Please enter new password to reset your password.</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<?php echo (!empty($new_password_err)) ? 'Error' : ''; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				</div>
					<input name="new_password" class="form-control" placeholder="New Password" type="password" value="<?php echo $new_password; ?>">
					<span class="help-block"><?php echo $new_password_err; ?></span>
				</div>
			<?php echo (!empty($confirm_password_err)) ? 'Error' : ''; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				</div>
				<input name="confirm_password" class="form-control" placeholder="Confirm Password" type="password">
				<span class="help-block"><?php echo $confirm_password_err; ?></span>
			</div>
			<div class="form-group">
				<input name="changepass" type="submit" class="btn btn-primary btn-block" value="Change Password">
				<a class="btn btn-link" href="home.php">Cancel</a>
			</div>
		</form>
	</div>
	<?php echo copyright(2019); ?>
</body>
</html>
