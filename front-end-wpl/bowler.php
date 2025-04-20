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
        $bowling_style = $_POST['style'];
        $bowling_type = $_POST['type'];
        $matches = $_POST['matches'];
        $wickets = $_POST['wickets'];
        $economy_rate = $_POST['average'];
        $fifers = $_POST['Fifers'];
        $best_bowling_figures = $_POST['Best_Bowling_Figures'];

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
        $sql = "INSERT INTO Bowler (name, age, city, club_name, role, bowling_style, bowling_type, matches, wickets, economy_rate, fifers, best_bowling_figures, image_link)
                VALUES (:name, :age, :city, :club_name, :role, :bowling_style, :bowling_type, :matches, :wickets, :economy_rate, :fifers, :best_bowling_figures, :image_link)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':age' => $age,
            ':city' => $city,
            ':club_name' => $club_name,
            ':role' => $role,
            ':bowling_style' => $bowling_style,
            ':bowling_type' => $bowling_type,
            ':matches' => $matches,
            ':wickets' => $wickets,
            ':economy_rate' => $economy_rate,
            ':fifers' => $fifers,
            ':best_bowling_figures' => $best_bowling_figures,
            ':image_link' => $image_link
        ]);

        // Get the last inserted ID
        $last_id = $conn->lastInsertId("bowler_id_seq");  // Use your actual sequence name

        // Redirect to profile page
        header("Location: profile.html?id=" . $last_id);
        exit;

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
