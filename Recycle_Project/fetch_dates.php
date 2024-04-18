<?php
$host = 'localhost:3306';
$dbname = 'recycling';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT DISTINCT DATE(time) as date FROM recycling";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($results);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
