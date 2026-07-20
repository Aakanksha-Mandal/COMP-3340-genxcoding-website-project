<?php
// config.example.php
//
// Template for config.php - copy this file to config.php and fill in your
// own real database credentials. config.php itself is listed in .gitignore
// and should NEVER be committed to a GitHub repo with real credentials
// in it (this is standard practice: the actual config.php with real
// passwords lives only on your web server and your own machine, never in
// version control).
//
// Setup:
//   1. cp config.example.php config.php
//   2. Fill in the 4 values below with your real database info
//   3. Set BASE_URL to match the folder your project lives in on the server

$db_host = "localhost";
$db_user = "your_db_username";
$db_pass = "your_db_password";
$db_name = "your_db_name";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// BASE_URL - the folder path your project lives in on the server.
// IMPORTANT: this must start with a leading slash (and have NO trailing
// slash) so the browser treats it as an absolute path from the domain
// root - every place that uses BASE_URL already appends its own leading
// "/" before the filename (e.g. BASE_URL . '/index.php'), so a missing
// leading slash turns every link into a broken relative path instead.
//
// Examples:
//   Site loads at http://localhost/genxcoding/                    -> '/genxcoding'
//   Site loads at the server root (no subfolder)                  -> ''
//   Site loads at https://youruser.myweb.cs.uwindsor.ca/Project/x -> '/Project/x'
define('BASE_URL', '/genxcoding');

session_start();
