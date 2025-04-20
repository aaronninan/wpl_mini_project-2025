<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = "localhost";
    $port = "5432";
    $dbname = "cricket_hub";
    $user = "postgres";
    $password = "Aditya@2005";

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Collect form data
        $name = $_POST['name'];
        $age = $_POST['age'];
        $city = $_POST['location'];
        $club_name = $_POST['club_name'];
        $role = $_POST['role'];
        $batting_style = $_POST['style'];
        $bowling_style = $_POST['bowling_style'];
        $bowling_type = $_POST['bowling_type'];
        $matches = $_POST['matches'];
        $runs = $_POST['runs'];
        $wickets = $_POST['wickets'];
        $economy = $_POST['economy'];
        $average = $_POST['average'];
        $strike_rate = $_POST['strike_rate'];
        $highest_score = $_POST['highest_score'];
        $best_bowling = $_POST['best_bowling'];

        // Handle image upload
        $image_link = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmp = $_FILES['image']['tmp_name'];
            $imageName = uniqid() . '_' . $_FILES['image']['name'];
            $imagePath = "uploads/" . $imageName;

            if (move_uploaded_file($imageTmp, $imagePath)) {
                $image_link = $imagePath;
            }
        }

        // Prepare SQL query
        $sql = "INSERT INTO Allrounder (
                    name, age, city, club_name, role, batting_style, bowling_style, bowling_type,
                    matches, runs, wickets, economy_rate, batting_average, strike_rate,
                    highest_score, best_bowling_figures, image_link
                ) VALUES (
                    :name, :age, :city, :club_name, :role, :batting_style, :bowling_style, :bowling_type,
                    :matches, :runs, :wickets, :economy, :average, :strike_rate,
                    :highest_score, :best_bowling, :image_link
                )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':age' => $age,
            ':city' => $city,
            ':club_name' => $club_name,
            ':role' => $role,
            ':batting_style' => $batting_style,
            ':bowling_style' => $bowling_style,
            ':bowling_type' => $bowling_type,
            ':matches' => $matches,
            ':runs' => $runs,
            ':wickets' => $wickets,
            ':economy' => $economy,
            ':average' => $average,
            ':strike_rate' => $strike_rate,
            ':highest_score' => $highest_score,
            ':best_bowling' => $best_bowling,
            ':image_link' => $image_link
        ]);

        // Get the last inserted ID (adjust sequence name if needed)
        $last_id = $conn->lastInsertId("allrounder_id_seq");

        // Redirect to profile page
        header("Location: profile.html?id=" . $last_id);
        exit;

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
