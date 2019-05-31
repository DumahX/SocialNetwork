<?php require_once 'data/db_conn.php'; ?>
<?php require 'functions/functions.php'; ?>
<?php
	$errors = [];
	$missing = [];
	$success = "";
	if (isset($_POST['signup'])) {
		$required = ['fname', 'lname', 'username', 'email', 'password', 'compass'];
		require 'functions/validate_user.php';
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>The Network - Welcome</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="styles/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<body>

<div class="container">
	<h1 class="text-center">Welcome to The Network</h1>
	<div class="card bg-light">
	<article class="card-body mx-auto" style="max-width: 400px;">
		<h4 class="card-title mt-3 text-center">Create an account</h4>
		<?php if ($_POST && $errors) : ?>
			<div class="alert alert-danger" role="alert">
				Please fix the item(s) indicated
			</div>
		<?php endif; ?> 
		<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
			<?php if ($missing && in_array('fname', $missing)) : ?>
					<div class="alert alert-danger" role="alert">
						Please enter your first name
					</div>
			<?php endif; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-user"></i> </span>
				</div>
				<input name="fname" class="form-control" placeholder="First Name" type="text"
					<?php
					if ($errors || $missing) {
						echo 'value="' . htmlentities($fname) . '"';
					}
					?>
				>
			</div>
			<?php if ($missing && in_array('lname', $missing)) : ?>
				<div class="alert alert-danger" role="alert">
					Please enter your last name
				</div>
			<?php endif; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-user"></i> </span>
				</div>
				<input name="lname" class="form-control" placeholder="Last Name" type="text"
					<?php
						if ($errors || $missing) {
							echo 'value="' . htmlentities($lname) . '"';
						}
					?>
				>
			</div>
			<?php if ($missing && in_array('username', $missing)) : ?>
				<div class="alert alert-danger" role="alert">
					Please enter your username
				</div>
			<?php elseif (isset($errors['userexist'])) : ?>
				<div class="alert alert-danger" role="alert">
					Username already exists, please enter another
				</div>
			<?php endif; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-user"></i> </span>
				</div>
				<input name="username" class="form-control" placeholder="Username" type="text"
					<?php
						if ($errors || $missing) {
							echo 'value="' . htmlentities($username) . '"';
						}
					?>
				>
			</div>
			<?php if ($missing && in_array('email', $missing)) : ?>
				<div class="alert alert-danger" role="alert">
					Please enter your email
				</div>
			<?php elseif (isset($errors['emailexist'])) : ?>
				<div class="alert alert-danger" role="alert">
					Email already exists, please enter another
				</div>
			<?php endif; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
				</div>
				<input name="email" class="form-control" placeholder="Email address" type="email"
					<?php
						if ($errors || $missing) {
							echo 'value="' . htmlentities($email) . '"';
						}
					?>
				>
			</div>
			<?php if ($missing && in_array('password', $missing)) : ?>
				<div class="alert alert-danger" role="alert">
					Please enter your password
				</div>
			<?php elseif (isset($errors['shortpass'])) : ?>
				<div class="alert alert-danger" role="alert">
					Password must be at least 6 characters
				</div>
			<?php endif; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				</div>
				<input name="password" class="form-control" placeholder="Password" type="password">
			</div>
			<?php if ($missing && in_array('compass', $missing)) : ?>
				<div class="alert alert-danger" role="alert">
					Please re-enter your password
				</div>
			<?php elseif (isset($errors['passmatch'])) : ?>
				<div class="alert alert-danger" role="alert">
					Please make sure your passwords match
				</div>
			<?php endif; ?>
			<div class="form-group input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
				</div>
				<input name="compass" class="form-control" placeholder="Confirm password" type="password">
			</div>
			<div class="form-group">
				<input name="signup" type="submit" class="btn btn-primary btn-block" value="Sign Up">
			</div>
			<?= $success; ?>
			<p class="text-center">Have an account? <a href="login.php">Login</a> </p>
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