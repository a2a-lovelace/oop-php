<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
	header("Location: login.php");
	exit();
}

require_once 'classes.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$u_id = $_POST['u_id'] ?? '';
	$dep_id = $_POST['dep_id'] ?? '';
	$pos_name = $_POST['pos_name'] ?? '';

	$querier = new Toolkit();
	$id = $querier->addUserPos($u_id, $dep_id, $pos_name);

}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Add new department</title>
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
		<p>Add a new position to a user:</p>
		<form method = 'post'>
		<p>User id: <input type = 'number' name = 'u_id' required></p>
		<p>Deparment id: <input type = 'number' name = 'dep_id' required></p>
		<p>Position name: <input type = 'text' name = 'pos_name' required></p>
		<input type = 'submit' value = 'Create'></form>
		<?php if (isset($id)): ?>
		    <p><?php echo 'The position has a number: '.$id; ?></p>
		<?php endif; ?>
	</div>
<?php } ?>
</body>