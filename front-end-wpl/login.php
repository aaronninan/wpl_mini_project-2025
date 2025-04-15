<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Database connection
    $host = "localhost";
    $port = "5432";
    $dbname = "cricket_hub";
    $user = "postgres";
    $dbPassword = "Aditya@2005"; // renamed to avoid conflict with $password

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query the user
        $stmt = $conn->prepare("SELECT * FROM user_info WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Use plain text match for now (or use password_verify if stored hashed)
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['email'] = $user['email']; // You can store user ID or name too
            header("Location: home.html");
            exit();
        } else {
            echo "Invalid email or password.";
        }
        
    } catch (PDOException $e) {
        echo "Database connection error: " . $e->getMessage();
        exit;
    }
} else {
    echo "Please submit the form.";
}
?>
