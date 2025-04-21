
<html>
    <head>
        <title>

        </title>
        <style>
.view a{
    color:white;
    text-decoration:none;
}
.view{
    border:2px solid white;
    display:inline-block;
    background-color: black;
    border-radius:20px;
    padding:10px 20px;

}
.view:hover{
    background-color: gray;
}
        </style>
    </head>
    <body>
   
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

if (!isset($_GET['category'])) {
    exit("Category not provided.");
}

$category = $_GET['category'];
$allowed = ['batsman', 'bowler', 'allrounder', 'wicketkeeper'];

if (!in_array($category, $allowed)) {
    exit("Invalid category.");
}

// Query to get all players from the selected category
$query = "SELECT * FROM $category ORDER BY id DESC";
$result = pg_query($conn, $query);

if (!$result) {
    echo "<p>Error fetching data.</p>";
    exit;
}

while ($row = pg_fetch_assoc($result)) {
    echo '<div class="player-card">
            <img src="' . htmlspecialchars($row['image_link']) . '" alt="' . htmlspecialchars($row['name']) . '">
            <h4>' . htmlspecialchars($row['name']) . '</h4>
            <p>From: ' . htmlspecialchars($row['city']) . '</p>
            <p>Club: ' . htmlspecialchars($row['club_name']) . '</p>
            <p>Role: ' . htmlspecialchars($row['role']) . '</p>';

    echo '<div class="view">
            <a href="view_profile.php?id=' . $row['id'] . '&category=' . $category . '" class="btn">View Profile</a>
          </div>
        </div>';
}

pg_close($conn);
?>
 </body>
 </html>