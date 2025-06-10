<?php
session_start();
require_once 'classes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $f = $_POST['f'] ?? '';
    $i = $_POST['i'] ?? '';
    $o = $_POST['o'] ?? '';

    $user = new User($username, $email, $password, $f, $i, $o);
    $registration_result = $user->register();

    if (is_numeric($registration_result)) {
        // Registration successful, redirect to login
        //$error = "reg res: ".$registration_result;
        header("Location: login.php");
        exit();
    } else {
        // Registration failed, show an error
        $error = 'error: '.$registration_result; // The register() method returns an error message
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
<h2>Register</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="post" action="">
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    First Name: <input type="text" name="f" required><br>
    Second Name: <input type="text" name="i" required><br>
    Family Name: <input type="text" name="o" required><br>
    <input type="submit" value="Register">
</form>
<p>Already have an account? <a href="login.php">Then go and log straight in!</a>.</p>
</body>
</html>
