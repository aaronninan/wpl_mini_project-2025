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
        $matches = $_POST['matches'];
        $runs = $_POST['runs'];
        $dismissals = $_POST['wickets_behind_stumps'];
        $batting_average = $_POST['batting_average'];
        $strike_rate = $_POST['strike_rate'];
        $highest_score = $_POST['highest_score'];

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

        // Prepare SQL query for Wicketkeeper
        $sql = "INSERT INTO Wicketkeeper (name, age, city, club_name, role, batting_style, matches, runs, dismissals, batting_average, strike_rate, highest_score, image_link)
                VALUES (:name, :age, :city, :club_name, :role, :batting_style, :matches, :runs, :dismissals, :batting_average, :strike_rate, :highest_score, :image_link)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':age' => $age,
            ':city' => $city,
            ':club_name' => $club_name,
            ':role' => $role,
            ':batting_style' => $batting_style,
            ':matches' => $matches,
            ':runs' => $runs,
            ':dismissals' => $dismissals,
            ':batting_average' => $batting_average,
            ':strike_rate' => $strike_rate,
            ':highest_score' => $highest_score,
            ':image_link' => $image_link
        ]);

        // Get the last inserted ID (adjust sequence name if needed)
        $last_id = $conn->lastInsertId("wicketkeeper_id_seq");

        // Redirect to profile page
        header("Location: profile.html?id=" . $last_id);
        exit;

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
