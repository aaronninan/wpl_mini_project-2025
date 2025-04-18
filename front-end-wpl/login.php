<?php
session_start();
// Get and clear the error message at the beginning

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
} else {
    $error = "";
}

unset($_SESSION['error']);  // This must come right after getting it

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Database connectionS
    $host = "localhost";
    $port = "5432";
    $dbname = "cricket_hub";
    $user = "postgres";
    $dbPassword = "Aditya@2005";

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM user_info WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            header("Location: home.html");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            $_SESSION['old_email'] = $email;
            header("Location: login.php");
            exit();
        }

    }
    catch (PDOException $e) {
        $_SESSION['error'] = "Database error!";
        header("Location: login.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Cricket Talent Hub</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
    </style>
  <style>
    *{
      font-family: "Rubik", sans-serif;
      color: white;
    }
    body {
     background-image: url("login-bg.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height:90vh;
    }
    .nav ul {
      display: flex;
      list-style: none;
      gap:30px;
      padding: 20px;
    }
    .nav ul li:hover{
     background-color: gray;
    }
    a{
      text-decoration: none;
    }
    .head{
      display: flex;
      justify-content: center;
      font-size: 30px;
      margin-bottom: 275px;
      position: relative;
      top:65px;
    }
    .box{
      border: 2px solid black;
     height:550px;
     width:500px;
     margin-left: 500px;
     margin-top:10px ;
     background-color:rgba(0 0 0 0.2);
     border-radius: 20px;
     box-shadow: 0 50px 110px;
     display: flex;
     justify-content: center;
     align-items: center;
    }
    label{
      font-size: larger;
    }
    .email{
      margin-top: 10px;
      font-weight: bolder;
    }
    .email-in{
      position: relative;
      top:-210px;
      bottom: 100px;
      left: 45px;
    }
    .password{
      margin-top: 10px;
      font-weight: bolder;
    }
    .pass-in{
      position: relative;
      bottom: 175px;
      left: 45px;

    }
    .button  button{
      color: white;
      display: inline-block;
      border: 2px solid white;
      font-weight: bold;
      font-size:large;
    }
    .button  button:hover{
      background-color: gray;
  
    }
    .button{
      margin-left: 48px;
      position: relative;
      top:-130px;
      left:25px;
    }
    button{
      border: 2px solid black;
      border-radius: 50px;
      padding: 10px 40px;
      background-color: black;
      cursor: pointer;
    }
    input{
      border: 2px solid black;
      border-radius: 50px;
      padding: 10px 20px;
      color: black;
    }
    a{
      list-style: none;
      text-decoration: none;
    }
    .p{
      position: relative;
      top:-90px;
      left:10px;
    }
    .p a{
      color:white;
      font-weight: bold;
      background-color:  black;
      border-radius: 50px;
      border: 3px solid white;
      padding: 10px 20px;
      position: relative;
      bottom: 25px;
      left: 10px;
    }
    .p a:hover{
      background-color: gray;
    }
    .foot{
      display: inline-block;
      position: relative;
      left:580px;
      top:50px;
    }
    .p p{
      color: white;
      font-weight: bold;
      font-size: 15px;

    }
    select{
      color: white;
      border: 2px solid white;
      border-radius: 50px;
      padding: 10px 40px;
      background-color: black;
      font-weight: bold;
      font-size:large;
    }
    select option{
      color:white;

}
.link{
  display: flex;
  gap:10px;
}
.p p:nth-child(1){
  font-size: 20px;
  position: relative;
  bottom: 25px;
}
.error-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;

        }
        .php{
            position: relative;
            bottom:220px;
            display: flex;
            align-items:center;
            left:20px;
        }
  </style>
  <script src="script.js" defer></script>
</head>
<body>
  <header>
      <nav>
        <div class="nav">
          <nav>
          <ul>
          <li><a href="home.html">Home</a></li>
          <li><a href="profile.html"> Profiles</a></li>
          <li><a href="About.html"> About Us</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <section class="login">
    <div class="box">
      <form  method="POST">

        <div class="head"><h1>LOGIN</h1></div>
        <div class="php">
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
      
        </div>

        <div class="email-in">
          <label for="email">Email:</label>
          <div class="email">
            <input type="email" id="email" name="email" placeholder="Email" required>
          </div>
        </div>
      
        <div class="pass-in">
          <label for="password">Password:</label>
          <div class="password">
            <input type="password" id="password" name="password" placeholder="Password" required>
          </div>
        </div>
      
        <div class="button">
          <button type="submit">Login</button>
        </div>
      
        <div class="p">       
    <p>Don't have an account? Sign Up</p>
    <div class="link">
      <p></p>
      <p><a href="create-profile.html">As Player</a></p>
      <p><a href="sign_up_scout.html">As Scout</a></p>
    </div>

        </div>
      </form>
      

    </div>
  
  </section>


  <footer>
    <div class="foot">
      <p>&copy; 2025 Cricket Talent Hub. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
