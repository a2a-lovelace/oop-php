<?php
session_start();
require_once 'classes.php'; // Include your class files
$error = '';
$logs = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $user = new User('', $email, $password, '', '', ''); // Username and FIO can be filled after login

    $user_id = $user->login();
	$logs = 'id: '.$user_id;
    if ($user_id) {
        // Username is set in session in your User class
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<?php if ($logs): ?>
    <p style="color:green;"><?php echo htmlspecialchars($logs); ?></p>
<?php endif; ?>
<form method="post" action="">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
<p>No account yet? <a href="reg.php">Get one rigth here!</a>.</p>
</body>
</html>