<?php
$password = "test123"; // Your plain-text password
// id - player1@example.com

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Your hashed password is: " . $hashedPassword;
?>

