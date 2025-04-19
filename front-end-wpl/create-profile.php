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
            // ✅ Insert into profile_info
            $sql_profile = "INSERT INTO profile_info (email, password, name, age, role, location, notes) 
                            VALUES (:email, :password, :name, :age, :role, :location, :notes)";
            $stmt_profile = $conn->prepare($sql_profile);

            $stmt_profile->bindParam(':email', $email);
            $stmt_profile->bindParam(':password', $hashed_password);
            $stmt_profile->bindParam(':name', $name);
            $stmt_profile->bindParam(':age', $age);
            $stmt_profile->bindParam(':role', $role);
            $stmt_profile->bindParam(':location', $location);
            $stmt_profile->bindParam(':notes', $notes);

            if ($stmt_profile->execute()) {
                // ✅ Insert into user_info (just email, password, and role)
                $sql_user = "INSERT INTO user_info (email, password, role) 
                             VALUES (:email, :password, :role)";
                $stmt_user = $conn->prepare($sql_user);

                $stmt_user->bindParam(':email', $email);
                $stmt_user->bindParam(':password', $hashed_password);
                $stmt_user->bindParam(':role', $role);

                if ($stmt_user->execute()) {
                    $success_message = "Profile created successfully!";
                } else {
                    $errors[] = "Profile was created, but failed to save login credentials.";
                }
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
