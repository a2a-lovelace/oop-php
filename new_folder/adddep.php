<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
	header("Location: login.php");
	exit();
}

require_once 'classes.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$dep_name = $_POST['dep_name'] ?? '';

	$querier = new Toolkit();
	$id = $querier->createDep($dep_name);

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
		<p>Insert the name of a department you wish to add:</p>
		<form method = 'post'>
		<input type = 'text' name = 'dep_name' required>
		<input type = 'submit' value = 'Create'></form>
		<?php if (isset($id)): ?>
		    <p><?php echo 'Added department got an id: '.$id; ?></p>
		<?php endif; ?>
	</div>
<?php } ?>
</body>