<?php
// initialize the session
session_start();
$user_id = $_SESSION['id'];
require_once 'data/db_conn.php';
require 'functions/functions.php';

// check if the user is logged in, if not then redirect to login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	// throw them that redirect
	header("location: login.php");
	exit;
}
// set error and success message to empty values
$posterror = "";
$success = "";

// process data when user submits form
if($_SERVER["REQUEST_METHOD"] == "POST") {
	// make sure post isn't empty
	if(empty($_POST['post'])) {
		$posterror = "Please enter a post";
	} elseif(strlen($_POST['post']) < 25) {
		// make sure post is at LEAST 25 characters
		$posterror = "Please enter at least 25 characters";
	} else {
		$post = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_STRING);
	}
	// make sure there are no errors before uploading
	if(empty($posterror)) {
		// prepare insert statement
		$sql = "INSERT INTO posts(user_id, post) VALUES ('$user_id', '$post')";
		mysqli_query($db, $sql);
		$success = "Post was successfully made";
	}
}

// retrieve posts from database
$showPosts = "SELECT posts.post, users.id, users.first_name FROM posts JOIN users USING (id) ORDER BY posts.post DESC";
// set result to a variable
$postResult = $db->query($showPosts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>The Network - Home</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<div class="page-header text-center">
		<h1>The Network</h1>
		<h2 class="text-center">Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h2>
	</div>
		<div class="navbar-home">
  			<a class="active" href="#"><i class="fa fa-fw fa-home"></i> Home</a> 
  			<a href="#"><i class="fa fa-fw fa-search"></i> Search</a> 
  			<a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
		</div>
		<div class="card bg-light">
		<article class="card-body mx-auto" style="max-width: 700px;">
			<h4 class="card-title mt-3 text-center">Make a post</h4>
			<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
			<?php if (!empty($posterror)) {
				echo $posterror;
			} ?>
				<div class="form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"> <i class="fas fa-pen-square"></i> </span>
						
					</div>
					<textarea class="md-textarea form-control" rows="12" name="post"></textarea>
				</div>
				<div class="form-group">
					<input name="postbtn" type="submit" class="btn btn-primary btn-block" value="Post">
				</div>
				<?php echo $success; ?>
			</form>
		</article>
		<article class="card-body mx-auto" style="max-width: 400px;">
			<h4 class="card-title mt-3 text-center">Recent Posts</h4>
			<?php while ($row = mysqli_fetch_array($postResult)) { ?>
				<h4>Post by: <?php echo $row['first_name']; ?></h4>
				<p><?php echo $row['post']; ?>
			<?php } ?>
		</div>
		<footer>
			<?php echo copyright(2019); ?>
		</footer>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.min.js"></script>
</body>
</html>