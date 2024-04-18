<?php
$host = 'localhost:3306';
$dbname = 'recycling';
$username = 'root';
$password = '';

$dateFilter = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Default to today

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT DISTINCT DATE(time) as Date, Name, Items, Recyclable FROM recycling WHERE DATE(time) = :date";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $dateFilter, PDO::PARAM_STR);

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo "No records found for the selected date.";
    } 

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #48bf53;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-image: url("3r6.png");
            background-size: cover;
            background-position: no-repeat;
        }

        .overlay {
            background-color: rgba(255, 255, 255, 0.7);
            width: 75%;
            height: 75vh;
            position: absolute;
            top: 13%;
            left: 14%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            background-color: #02231c;
            width: 100%;
            z-index: 1;
        }

        .navbar a.right {
            order: -1;
            margin-right: auto;
            font-weight: bold;
            font-family: 'Open Sans', sans-serif;
            font-size: 30px;
            color: #f6f7f6;
        }

        .navbar a {
            color: #f6f7f6;
            text-decoration: none;
            padding: 1px 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #81c490;
        }

        .navbar a.active {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-top: 20px;
            z-index: 1;
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            color: #02231c;
            font-style: italic;
        }

        th {
            background-color: #4CAF50;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #e8e8e8;
        }

        tr:nth-child(odd) {
            background-color: #f5f5f5;
        }

        button {
            background-color: #11823b;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            margin-top: 20px;
            z-index: 1;
        }

    </style>
</head>

<body>

    <div class="navbar">
        <a class="right">Report</a>
        <a class="nav-link scrollto active" href="#hero"><span class="fa fa-home"> Home </span></a>
        <a class="nav-link scrollto" href="#about"><span class="fa fa-info-circle" aria-hidden="true"> About us</span></a>
        <a class="nav-link scrollto" href="contact"><span class="fas fa-phone"> Contact</span></a>
    </div>

    <div class="overlay">
    <div class="date-filter">
    <label for="date">Select Date:</label>
    <input type="date" id="date" name="date">
    <button onclick="filterByDate()">Filter</button>
</div>

        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>No. of Items</th>
                    <th>Recyclable or not?</th>
                </tr>
            </thead>
            <tbody>
            <?php
    if (!empty($results)) {
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Items']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Recyclable']) . "</td>";
            echo "</tr>";
        }
    }
    ?>
            </tbody>
</table>
        <button onclick="navigateToHandler()">Move to Home Page</button>
    </div>
    
    

</body>
</script>
      function filterByDate() 
    {
        const selectedDate = document.getElementById("date").value;
        window.location.href = `report.php?date=${selectedDate}`;
    }
        function navigateToHandler()
         {
            
            window.location.href = 'Home.html';
        }
</script>

</html>
