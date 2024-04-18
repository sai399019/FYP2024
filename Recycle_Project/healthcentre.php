<?php
session_start();

// Database connection
$host = 'localhost:3306';
$dbname = 'recycling';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get selected items from form
        $selectedItems = [];
        if (isset($_POST['item1'])) $selectedItems[] = $_POST['item1'];
        if (isset($_POST['item2'])) $selectedItems[] = $_POST['item2'];
        if (isset($_POST['item3'])) $selectedItems[] = $_POST['item3'];
        if (isset($_POST['item4'])) $selectedItems[] = $_POST['item4'];
        if (isset($_POST['item5'])) $selectedItems[] = $_POST['item5'];
        if (isset($_POST['item6'])) $selectedItems[] = $_POST['item6'];
       

        // Loop through selected items
        foreach ($selectedItems as $item) {
            // Fetch data from academic building/crc
            $sql = "SELECT `Type of Plastic`, Weight, Recyclable, Treatment FROM `health centre` WHERE Name = :item_name";

            $stmtAcademic = $pdo->prepare($sql);
            $stmtAcademic->bindParam(':item_name', $item);
            $stmtAcademic->execute();
            $data = $stmtAcademic->fetch(PDO::FETCH_ASSOC);

            // Check if data is fetched
            if ($data) {
                // Assign fetched data
                $type = $data['Type of Plastic'] ?? '';
                $weight = $data['Weight'] ?? '';
                $recyclable = $data['Recyclable'] ?? '';
                $treatment = $data['Treatment'] ?? '';

                // Prepare SQL statement to insert into recycling table
                $stmt = $pdo->prepare("INSERT INTO recycling (Name, type, Weight, Recyclable, Treatment, date) VALUES (:item_name, :type, :weight, :recyclable, :treatment, CURDATE()) ON DUPLICATE KEY UPDATE Items = Items + 1");

                $stmt->bindParam(':item_name', $item);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':weight', $weight);
                $stmt->bindParam(':recyclable', $recyclable);
                $stmt->bindParam(':treatment', $treatment);

                $stmt->execute();
            } else {
                echo "Data not found for item: $item<br>";
            }
        }

        // Store selected items in session
        $_SESSION['selectedItems'] = $selectedItems;

        // Redirect to enter-pieces.php
        header('Location: enter-pieces.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
