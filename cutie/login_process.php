<?php
session_start();

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$ticketType = $_POST['ticket_type'] ?? 'General Admission';

if (empty($name) || empty($email)) {
    header("Location: index.php?error=empty_fields");
    exit;
}


$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;


setcookie('preferred_ticket_type', $ticketType, time() + (86400), "/");

header("Location: booking.php");
exit;