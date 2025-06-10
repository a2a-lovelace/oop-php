<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
	header("Location: login.php");
	exit();
}

require_once 'classes.php';

$querier = new Toolkit();
$res_rows = $querier->getDepList();
$ans = 'List of departments:<br><br>';
if ($res_rows) {

$ans = $ans."<table border = '1' cellpadding = '10', cellspacing = '0'><tr><td>id</td><td>department</td></tr>";
foreach ($res_rows as $row) {
	$ans = $ans.'<tr>';
	foreach ($row as $item) {
		$ans = $ans.'<td>'.htmlspecialchars($item).'</td>';
	}
	$ans = $ans.'</tr>';
}
$ans = $ans.'</table>';
} else {
$ans = $ans.'No departments in the database';
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Get Departments List</title>
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
		<?php if (isset($ans)): ?>
		    <p><?php echo $ans; ?></p>
		<?php endif; ?>
	</div>
<?php } ?>
</body>