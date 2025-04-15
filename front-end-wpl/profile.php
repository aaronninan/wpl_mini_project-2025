<?php
$success_message = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $role = $_POST['role'] ?? '';
    $location = $_POST['location'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $host = "localhost";
    $port = "5432";
    $dbname = "cricket_hub";
    $user = "postgres";
    $password_db = "Aditya@2005";

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ✅ Check if email already exists
        $check_stmt = $conn->prepare("SELECT 1 FROM profile_info WHERE email = :email");
        $check_stmt->bindParam(':email', $email);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $errors[] = "This email is already registered. Please use a different one.";
        } else {
            // ✅ Insert new profile
            $sql = "INSERT INTO profile_info (email, password, name, age, role, location, notes) 
                    VALUES (:email, :password, :name, :age, :role, :location, :notes)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':notes', $notes);

            if ($stmt->execute()) {
                $success_message = "Profile created successfully!";
            } else {
                $errors[] = "Something went wrong. Please try again.";
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile Registration Result</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      padding: 20px;
    }
    .message {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .error {
      color: red;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .success {
      color: green;
      font-weight: bold;
      margin-bottom: 15px;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="message">
    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach ($errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
      <a href="create-profile.html">Go back to registration form</a>
    <?php elseif (!empty($success_message)): ?>
      <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
      <a href="home.html">Go to Home</a>
    <?php endif; ?>
  </div>
</body>
</html>
