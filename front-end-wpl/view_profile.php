<!DOCTYPE html>
<html>
<head>
    <title>Player Profile</title>
    <style>
        body 
        {
        font-family: Arial; padding: 20px; 
        background-image:url("view.avif");
        background-repeat: no-repeat;
        background-size: cover;
        font-weight:bolder;
            }
        .profile-card {
            background: #ffffffa3;
            border: 5px solid black;
            border-radius: 10px;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0px 10px 30px white;
            text-align: center;
        }
        .profile-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid black;
            margin-bottom: 15px;
        }
        .info p { margin: 6px 0; }
        h3 { margin-top: 20px; color: #2c3e50; }
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

if (!isset($_GET['id']) || !isset($_GET['category'])) {
    exit("ID or category not provided.");
}

$id = $_GET['id'];
$category = $_GET['category'];
$allowed = ['batsman', 'bowler', 'allrounder', 'wicketkeeper'];

if (!in_array($category, $allowed)) {
    exit("Invalid category.");
}

$query = "SELECT * FROM $category WHERE id = $1";
$result = pg_query_params($conn, $query, [$id]);

if (!$result || pg_num_rows($result) === 0) {
    exit("Player not found.");
}

$row = pg_fetch_assoc($result);
?>

<div class="profile-card">
    <img src="<?= htmlspecialchars($row['image_link']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
    <h3><?= htmlspecialchars($row['name']) ?></h3>
    <p><strong>City:</strong> <?= htmlspecialchars($row['city']) ?></p>
    <p><strong>Club:</strong> <?= htmlspecialchars($row['club_name']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($row['role']) ?></p>

<?php
if ($category == 'batsman') {
    echo '
        <p><strong>Batting Style:</strong> ' . htmlspecialchars($row['batting_style']) . '</p>
        <p><strong>Matches:</strong> ' . htmlspecialchars($row['matches']) . '</p>
        <p><strong>Runs:</strong> ' . htmlspecialchars($row['runs']) . '</p>
        <p><strong>Average:</strong> ' . (isset($row['average']) ? htmlspecialchars($row['average']) : 'N/A') . '</p>
        <p><strong>Strike Rate:</strong> ' . htmlspecialchars($row['strike_rate']) . '</p>
        <p><strong>Highest Score:</strong> ' . htmlspecialchars($row['highest_score']) . '</p>';
}

if ($category == 'bowler') {
    echo '
        <p><strong>Bowling Style:</strong> ' . htmlspecialchars($row['bowling_style']) . '</p>
        <p><strong>Bowling Type:</strong> ' . htmlspecialchars($row['bowling_type']) . '</p>
        <p><strong>Matches:</strong> ' . htmlspecialchars($row['matches']) . '</p>
        <p><strong>Wickets:</strong> ' . htmlspecialchars($row['wickets']) . '</p>
        <p><strong>Economy Rate:</strong> ' . htmlspecialchars($row['economy_rate']) . '</p>
        <p><strong>Fifers:</strong> ' . htmlspecialchars($row['fifers']) . '</p>
        <p><strong>Best Bowling:</strong> ' . htmlspecialchars($row['best_bowling_figures']) . '</p>';
}

if ($category == 'allrounder') {
    echo '
        <p><strong>Batting Style:</strong> ' . htmlspecialchars($row['batting_style']) . '</p>
        <p><strong>Bowling Style:</strong> ' . htmlspecialchars($row['bowling_style']) . '</p>
        <p><strong>Bowling Type:</strong> ' . htmlspecialchars($row['bowling_type']) . '</p>
        <p><strong>Matches:</strong> ' . htmlspecialchars($row['matches']) . '</p>
        <p><strong>Runs:</strong> ' . htmlspecialchars($row['runs']) . '</p>
        <p><strong>Wickets:</strong> ' . htmlspecialchars($row['wickets']) . '</p>
        <p><strong>Batting Average:</strong> ' . htmlspecialchars($row['batting_average']) . '</p>
        <p><strong>Strike Rate:</strong> ' . htmlspecialchars($row['strike_rate']) . '</p>
        <p><strong>Highest Score:</strong> ' . htmlspecialchars($row['highest_score']) . '</p>
        <p><strong>Economy Rate:</strong> ' . htmlspecialchars($row['economy_rate']) . '</p>
        <p><strong>Best Bowling:</strong> ' . htmlspecialchars($row['best_bowling_figures']) . '</p>';
}

if ($category == 'wicketkeeper') {
    echo '
        <p>Batting Style: ' . htmlspecialchars($row['batting_style']) . '</p>
        <p>Matches: ' . htmlspecialchars($row['matches']) . '</p>
        <p>Runs: ' . htmlspecialchars($row['runs']) . '</p>
        <p>Dismissals: ' . htmlspecialchars($row['dismissals']) . '</p>
        <p>Batting Average: ' . htmlspecialchars($row['batting_average']) . '</p>
        <p>Strike Rate: ' . htmlspecialchars($row['strike_rate']) . '</p>
        <p>Highest Score: ' . htmlspecialchars($row['highest_score']) . '</p>';
}
?>

</div>
</body>
</html>
