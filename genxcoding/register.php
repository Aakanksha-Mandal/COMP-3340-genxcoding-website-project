<?php
// register.php - new account signup. New accounts get is_admin=0 and
// is_active=1 by default (see database.sql), so anyone who registers here
// is a regular customer, never an admin.
require_once 'config.php';
$pageTitle = "Register";
$pageDescription = "Create a free GenX Coding account to shop, check out, and leave product reviews.";
$pageKeywords = "register, sign up, create account, GenX Coding";
$helpLink = 'wiki/help3.php#register';
include 'includes/header.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // hash the password, NEVER store plain text passwords
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // check if username OR email is already taken - the users table also has
    // UNIQUE constraints on both as a second line of defense, but checking
    // here first lets us show a friendly error instead of the raw DB error
    // that used to crash this page with a fatal 500 when someone reused an
    // email address (mysqli throws on constraint violations by default).
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "That username is already taken.";
    } else {
        $email_check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($email_check) > 0) {
            $error = "An account with that email already exists.";
        } else {
            mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')");
            header("Location: login.php"); // send them straight to login after signing up
            exit;
        }
    }
}
?>

<div class="container">
<h1>Create an Account</h1>
<?php if ($error) echo "<p class='error'>$error</p>"; ?>

<form method="post">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn">Register</button>
</form>
</div>

<?php include 'includes/footer.php'; ?>
