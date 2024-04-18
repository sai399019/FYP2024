<?php
$host = 'localhost:3306';
$dbname = 'recycling';
$username = 'root';
$password = '';

$date = $_GET['date'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT Name, Result, Treatment, type, Items, Recyclable FROM recycling WHERE DATE(time) = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$date]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create table rows
    $output = '';
    foreach ($results as $row) {
        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($row['Name']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['Items']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['Recyclable']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['Result']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['Treatment']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['type']) . "</td>";
        $output .= "</tr>";
    }

    echo $output;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
