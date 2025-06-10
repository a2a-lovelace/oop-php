<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
	header("Location: login.php");
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home Page</title>
	<link rel = 'stylesheet' href = 'styles.css'>
</head>
<body>
<?php if ($is_logged_in) { ?>
	<div class = 'sidebar'>
		<a href = 'index.php'>Home</a>
		<a href = 'dep_by_id.php'>Get department by id</a>
		<a href = 'deplist.php'>Departments list</a>
		<a href = 'userpos.php'>Get users positions</a>
		<a href = 'adddep.php'>Add new department</a>
		<a href = 'setdepname.php'>Change a name of a department</a>
		<a href = 'addutod.php'>Add new position</a>
		<a href = 'delpos.php'>Delete users position</a>
		<a href = 'logout.php'>Log out</a>
	</div>
	<div class = 'content'>
		<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
		<p>Have a great day! :3 <a href='logout.php'>Logout</a></p>
		<p>Wikipedia link: <a href='https://ru.wikipedia.org/wiki/%D0%92%D0%B8%D0%BA%D0%B8'>wiki</a></p>
	</div>
<?php } ?>
</body>
</html>