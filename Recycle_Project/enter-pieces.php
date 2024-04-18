<?php
session_start();

// Check if selected items are in session
if (!isset($_SESSION['selectedItems'])) {
    echo "Session data not set. Redirecting...";
    header('Location: save-data.php');
    exit;
}

// Database connection
$host = 'localhost:3306';
$dbname = 'recycling';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form POST data: ";
    print_r($_POST);

    foreach ($_SESSION['selectedItems'] as $index => $item) {
        if (isset($_POST[$index])) {
            $pieces = $_POST[$index];
            echo "Item: $item, Pieces: $pieces<br>";

            $_SESSION['selectedItems'][$index] = $pieces; 

            $sql = "UPDATE recycling SET Items = Items + :pieces WHERE Name = :item_name AND date = CURDATE()";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':item_name', $item);
            $stmt->bindValue(':pieces', $pieces);
            $stmt->execute();


            $sqlResult = "UPDATE recycling SET Result = CAST(Weight AS DECIMAL(10, 2)) * Items WHERE Name = :item_name AND date = CURDATE()";
            $stmtResult = $pdo->prepare($sqlResult);
            $stmtResult->bindValue(':item_name', $item);
            $stmtResult->execute();

            $sqlDept = "UPDATE recycling
                        SET Dept = 
                            CASE 
                                WHEN Name IN (SELECT Name FROM `academic building/crc`) THEN 'Academic Building'
                                WHEN Name IN (SELECT Name FROM `all departments`) THEN 'All Departments'
                                WHEN Name IN (SELECT Name FROM `hostel block`) THEN 'Hostel Block'
                                WHEN Name IN (SELECT Name FROM `health centre`) THEN 'Health Centre'
                                WHEN Name IN (SELECT Name FROM `household`) THEN 'Household'
                                WHEN Name IN (SELECT Name FROM `sports complex`) THEN 'Sports Complex'
                                ELSE NULL
                            END";
            $stmtDept = $pdo->prepare($sqlDept);
            $stmtDept->execute();
        }
    }

    header('Location: report.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Data Transformation</title>

    <style>
       
        body {
            background-image: url('2.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1px 5px;
            background-color: rgba(146, 194, 156, 0.5);
        }

        .navbar a.right {
            order: -1;
            margin-right: auto;
            font-weight: bold;
            font-style: italic;
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

        .overlay {
            background-color: rgba(255, 255, 255, 0.5);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            width: fit-content; /* Adjust as needed */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .submit-button {
            background-color: #007b5f;
            color: #fff;
            border-radius: 4px;
            height: 38px;
            line-height: 38px;
            width: 100%;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #004c3f;
        }

        #items {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        input[type="number"] {
            border: 1px solid #02231c;
            border-radius: 4px;
            height: 38px;
            line-height: 38px;
            padding-left: 5px;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="navbar">
        <a class="right">Academic Building CRC</a>
        <a class="nav-link scrollto active" href="#hero"><span class="fa fa-home"> Home </span></a>
        <a class="nav-link scrollto" href="#about"><span class="fa fa-info-circle" aria-hidden="true"> About us</span></a>
        <a class="nav-link scrollto" href="contact"><span class="fas fa-phone"> Contact</span></a>
    </div>
    <div class="overlay">
        <h1>Enter Number of Pieces</h1>
        <form action="enter-pieces.php" method="post">
            <div id="items">
                <?php foreach ($_SESSION['selectedItems'] as $index => $item): ?>
                    <div>
                        <label for="<?php echo $item; ?>"><?php echo $item; ?>:</label>
                        <input type="number" name="<?php echo $index; ?>" id="<?php echo $item; ?>" value="<?php echo $item; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="submit" value="Submit" class="submit-button">
        </form>
    </div>
</body>
</html>

