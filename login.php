<?php require_once 'data/db_conn.php'; ?>
<?php require 'functions/functions.php'; ?>
<?php
	// initialize session
	session_start();
	// if user is already logged in, then redirect to home page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
		header("location: home.php");
		exit;
	}

	// define variables, and set them to empty values
	$username = $password = "";
	$username_err = $password_err = "";

	// process form data when submitted
	if($_SERVER["REQUEST_METHOD"] == "POST") {

		// check if username is empty
		if(empty($_POST['username'])) {
			$username_err = "Please enter username";
		} else {
			$username = $_POST['username'];
		}

		// check if password is empty
		if(empty($_POST['password'])) {
			$password_err = "Please enter your password";
		} else {
			$password = $_POST['password'];
		}

		// validate login
		if(empty($username_err) && empty($password_err)) {
			// prepare select statement
			$sql = "SELECT id, username, password FROM users WHERE username = ?";

			if ($stmt = mysqli_prepare($db, $sql)) {
				// bind variables to prepared statement as paramaters
				mysqli_stmt_bind_param($stmt, "s", $param_username);

				// set parameters
				$param_username = $username;

				// attempt to execute prepated statement
				if(mysqli_stmt_execute($stmt)) {
					// store result
					mysqli_stmt_store_result($stmt);

					// check if username exists, then verify password
					if (mysqli_stmt_num_rows($stmt) == 1) {
						// bind result variables
						mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
						if (mysqli_stmt_fetch($stmt)) {
							if(password_verify($password, $hashed_password)) {
								// correct password, start the session
								session_start();

								// store data in session variables
								$_SESSION["loggedin"] = true;
								$_SESSION["id"] = $id;
								$_SESSION["username"] = $username;

								// redirect to home page
								header("location: home.php");
							} else {
								// display an error message if password not valid
								$password_err = "The passsword was not correct";
							}
						}
					} else {
						// display error message if username doesn't exist
						$username_err = "Username was not correct";
					}
				} else {
					echo "Something went wrong. Please try again later";
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
		<title>The Network - Login</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="styles/bootstrap.min.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
	</head>

	<body>

	<div class="container">
		<h1 class="text-center">Login to The Network</h1>
		<div class="card bg-light">
		<article class="card-body mx-auto" style="max-width: 400px;">
			<h4 class="card-title mt-3 text-center">Login</h4>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
				<?php echo (!empty($username_err)) ? 'has-error' : ''; ?>
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-user"></i> </span>
					</div>
						<input name="username" class="form-control" placeholder="Username" type="text" value="<?php echo $username; ?>">
						<span class="help-block"><?php echo $username_err; ?></span>
				</div>
				<?php echo (!empty($password_err)) ? 'has-error' : ''; ?>
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
					</div>
						<input name="password" class="form-control" placeholder="Password" type="password">
						<span class="help-block"><?php echo $password_err; ?></span>
				</div>
				<div class="form-group">
					<input name="signup" type="submit" class="btn btn-primary btn-block" value="Login">
				</div>
				<p class="text-center">Don't have an account? <a href="index.php">Sign Up</a> </p>
			</form>
		</article>
		</div>
		<footer>
			<?php echo copyright(2019); ?>
		</footer>
	</div>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	</body>
</html>