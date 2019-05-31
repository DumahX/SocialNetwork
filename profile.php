<?php
// initialize the session
session_start();
require 'functions/functions.php';
require 'data/db_conn.php';

// check if the user is logged in, if not then redirect to login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	// throw them that redirect
	header("location: login.php");
	exit;
}

// define variables and initialize with empty values
$fnamerr = "";
$emailerr = "";
$usererr = "";
$id = $_SESSION['id'];
$success = "";

// process data if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(empty($_POST['fname'])) {
		$fnamerr = "Please enter a first name";
	} elseif(empty($_POST['email'])) {
		$emailerr = "Please enter an email";
	} 
	if($_POST['email']) {
		// make sure email isn't a duplicate
		$email = $_POST['email'];
		$checkEmails = mysqli_query($db, "SELECT * FROM users WHERE email='$email'");
		$getRows = mysqli_affected_rows($db);
		if ($getRows >= 1) {
			$emailerr = "This email is already registered, please enter another";
		}
	} 
	if($_POST['username']) {
		// make sure username isn't a duplicate
		$username = $_POST['username'];
		$checkUsers = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
		$getUserRows = mysqli_affected_rows($db);
		if ($getUserRows >= 1) {
			$usererr = "This username is already taken, please enter another";
		}
	} 
	if(empty($_POST['username'])) {
		// make sure username isn't empty
		if (empty($_POST['username'])) {
			$usererr = "Please enter a username";
		}
	} if(empty($fnameerr) && empty($emailerr) && empty($usererr)) {
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
		$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		// check if there any errors before uploading
			// set sql statement to update
			$fnamesql = "UPDATE users SET first_name = '$fname' WHERE id = '$id'";
			mysqli_query($db, $fnamesql);
			$usersql = "UPDATE users SET username = '$username' WHERE id = '$id'";
			mysqli_query($db, $usersql);
			$emailsql = "UPDATE users SET email = '$email' WHERE id = '$id'";
			mysqli_query($db, $emailsql);
			$success = "Profile successfully updated";
		}
	}
// get database information on user
$showProfile = "SELECT users.first_name, users.last_name, users.username, users.email FROM users WHERE id = '$id'";
$profileResult = $db->query($showProfile);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>The Network - <?php echo htmlspecialchars($_SESSION["username"]); ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="styles/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<div class="container">
		<h1 class="text-center">The Network</h1>
		<div class="navbar-home">
  			<a href="home.php"><i class="fa fa-fw fa-home"></i> Home</a> 
  			<a href="#"><i class="fa fa-fw fa-search"></i> Search</a> 
  			<a class="active" href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
		</div>
		<div class="card bg-light">
		<article class="card-body mx-auto">
		<h2 class="text-center">Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h2>
			<h3 class="card-title mt-3 text-center">Your Profile</h3>
				<?php while ($row = mysqli_fetch_array($profileResult)) { ?>
					<p>Name: <?php echo $row['first_name']; ?></p>
					<p>Username: <?php echo $row['username']; ?></p>
					<p>Email: <?php echo $row['email']; ?></p>
				<?php } ?>
			<h3 class="card-title mt-3 text-center">Update Profile</h3>
			<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
			<?php if (!empty($fnamerr)) {
				echo $fnamerr;
			} ?>
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-user"></i> </span>
					</div>
					<input name="fname" class="form-control" placeholder="Update first name" type="text">
				</div>
				<?php if (!empty($usererr)) {
					echo $usererr;
				}
				?>
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-user"></i> </span>
					</div>
					<input name="username" class="form-control" placeholder="Update username" type="text">
				</div>
				<?php if (!empty($emailerr)) {
					echo $emailerr;
				} ?>
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
					</div>
					<input name="email" class="form-control" placeholder="Update email" type="email">
				</div>
				<div class="form-group">
					<input name="update" type="submit" class="btn btn-primary btn-block" value="Update">
					<a class="btn btn-link" href="home.php">Cancel</a>
					<a class="btn btn-link" href="reset.php">Reset Password</a>
				</div>
				<?php echo $success; ?>
				</form>
			</article>
		</div>
		<footer>
			<?php echo copyright(2019); ?>
		</footer>
	</div>
</body>
</html>				