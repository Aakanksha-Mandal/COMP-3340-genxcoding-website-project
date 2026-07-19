<?php
// contact.php - a simple contact form plus an embedded interactive map
// (the map satisfies the "interactive map" multimedia requirement).
require_once 'config.php';
$pageTitle = "Contact";
$pageDescription = "Get in touch with GenX Coding - questions, support, and store location.";
$pageKeywords = "contact GenX Coding, customer support, developer merch help";
$helpLink = 'wiki/help1.php';
include 'includes/header.php';

// there's no outgoing email server configured on most student hosting
// (myweb often blocks PHP's mail() function), so messages are appended to
// a local text file instead. Good enough for a class project - a real
// deployment would swap this for a proper mail library or a database table.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $msg = htmlspecialchars($_POST['message']);
    $line = date('Y-m-d H:i:s') . " - $name: $msg\n";
    file_put_contents('contact_messages.txt', $line, FILE_APPEND);
    // redirect back to this same page (Post/Redirect/Get) instead of just
    // falling through to the render below - otherwise refreshing the page
    // resubmits the form and appends the same message to the file again.
    // The ?sent=1 flag (read below) shows the confirmation message after
    // the redirect, same trick used in newsletter.php.
    header("Location: contact.php?sent=1");
    exit;
}
?>

<div class="container">
<h1>Contact Us</h1>

<?php if (isset($_GET['sent'])) echo "<p style='color:#2e7d32;font-weight:600;'>Thanks, we got your message!</p>"; ?>

<form method="post">
    <label>Name:</label>
    <input type="text" name="name" required>
    <label>Message:</label>
    <textarea name="message" required></textarea>
    <button type="submit" class="btn">Send</button>
</form>

<h2>Visit Us</h2>
<!-- interactive map requirement - using an embedded google map iframe, no API key needed for basic embed -->
<iframe
    width="100%" height="400"
    style="border:1px solid var(--card-border); border-radius:6px; max-width:700px;"
    loading="lazy"
    src="https://maps.google.com/maps?q=University%20of%20Windsor&t=&z=13&ie=UTF8&iwloc=&output=embed">
</iframe>
</div>

<?php include 'includes/footer.php'; ?>
