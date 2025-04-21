<?php
$host = 'localhost';
$db   = 'cricket_hub';
$user = 'postgres';
$pass = 'Aditya@2005';
$charset = 'utf8mb4';

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

$conn = null;  // Declare it outside try block
try {
    $conn = new PDO($dsn, $user, $pass, $options);

    // Collect form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $role = $_POST['role'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 🔍 Check if email exists
    $checkStmt = $conn->prepare("SELECT email FROM user_info WHERE email = :email");
    $checkStmt->execute([':email' => $email]);

    if ($checkStmt->rowCount() > 0) {
        echo "<script>
            alert('This email is already registered. Please use a different email.');
            window.location.href = 'scout.html'; 
        </script>";
        exit();
    }

    // ✅ Continue with registration
    $conn->beginTransaction();

    // Insert into profile_info
    $stmt1 = $conn->prepare("
        INSERT INTO profile_info (email, password, name, age, role, location, notes)
        VALUES (:email, :password, :name, :age, :role, :location, :notes)
    ");
    $stmt1->execute([
        ':email' => $email,
        ':password' => $hashedPassword,
        ':name' => $name,
        ':age' => $age,
        ':role' => $role,
        ':location' => $location,
        ':notes' => $notes
    ]);

    // Insert into user_info
    $stmt2 = $conn->prepare("
        INSERT INTO user_info (email, password, role)
        VALUES (:email, :password, :role)
    ");
    $stmt2->execute([
        ':email' => $email,
        ':password' => $hashedPassword,
        ':role' => $role
    ]);

    $conn->commit();

    echo "<script>
        alert('Scout registered successfully!');
        window.location.href = 'login.php';
    </script>";
}
catch (PDOException $e) {
    // Only rollback if connection was successful
    if ($conn instanceof PDO) {
        $conn->rollBack();
    }
    echo "Error: " . $e->getMessage();
}

?>
