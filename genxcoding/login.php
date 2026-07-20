<?php
// login.php - customer login (separate from admin/login.php, which is a
// different gate that also requires is_admin = 1).
require_once 'config.php';
$pageTitle = "Login";
$pageDescription = "Log in to your GenX Coding account to check out, leave reviews, and view your order history.";
$pageKeywords = "login, sign in, GenX Coding account";
$helpLink = 'wiki/help3.php#login';
include 'includes/header.php';

$error = "";

// "next" lets other pages (like product.php) send guests here and then
// bounce them back to what they were doing once they log in.
$next = isset($_GET['next']) ? $_GET['next'] : (isset($_POST['next']) ? $_POST['next'] : 'index.php');
// basic safety check - only allow relative paths within this site, never a full URL
if (strpos($next, '://') !== false || strpos($next, '//') === 0) {
    $next = 'index.php';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_active'] == 0) {
            $error = "This account has been disabled by an admin.";
        } else {
            // login success - save info in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header("Location: " . $next);
            exit;
        }
    } else {
        $error = "Wrong username or password.";
    }
}
?>

<div class="container">
<h1>Login</h1>

<?php if ($next !== 'index.php' && !$error): ?>
    <p style="color: var(--text-muted); font-size: 14px;">Please log in to continue.</p>
<?php endif; ?>
<?php if ($error) echo "<p class='error'>$error</p>"; ?>

<form method="post">
    <input type="hidden" name="next" value="<?php echo htmlspecialchars($next); ?>">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

<?php include 'includes/footer.php'; ?>
