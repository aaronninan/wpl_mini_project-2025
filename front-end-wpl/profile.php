<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Player Profiles | Cricket Talent Hub</title>

  <link rel="stylesheet" href="style.css" />
  <script src="script.js" defer></script>
</head>
<body>
  <header>
    <div class="container">
      <h1>Cricket Talent Hub</h1>
      <nav>
        <ul>
          <li><a href="home.html">Home</a></li>
          <li><a href="profile.html" class="active">Profiles</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  
  <?php
$host = "localhost";
$port = "5432";
$dbname = "cricket_hub";
$user = "postgres";
$password = "Aditya@2005";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$query = "SELECT * FROM batsman ORDER BY id DESC";
$result = pg_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>All Players</title>
    <style>
        .player-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            margin: 10px;
            width: 200px;
            text-align: center;
        }
        .player-card img {
            width: 100%;
            border-radius: 10px;
        }
        .view .btn {
            background-color: #007bff;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2>All Players</h2>
<div style="display: flex; flex-wrap: wrap; gap: 15px;">
<?php
while ($row = pg_fetch_assoc($result)) {
    echo '
    <div class="player-card">
        <img src="' . htmlspecialchars($row['image_link']) . '" alt="' . htmlspecialchars($row['name']) . '">
        <h4>' . htmlspecialchars($row['name']) . '</h4>
        <p>' . htmlspecialchars($row['batting_style']) . ' | ' . htmlspecialchars($row['role']) . '</p>
        <p>' . htmlspecialchars($row['club_name']) . '</p>
        <div class="view">
            <a href="profile.php?id=' . $row['id'] . '" class="btn">View Profile</a>
        </div>
    </div>';
}
pg_close($conn);
?>
</div>

</body>
</html>

  <footer>
    <div class="container">
      <p>&copy; 2025 Cricket Talent Hub. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
