<?php require('header.php');
require('connection.php');
//require('session.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Generate Bill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
            text-align: center;
        }

        .bill-details {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }

        .bill-total {
            font-size: 24px;
            font-weight: bold;
        }

        .img-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .img-container img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="img-container">
            <img src="images/logoSalon.png" alt="Top Left">
            <img src="images/cmc.png" alt="Top Right">
        </div>

        <?php
        // Fetch appointments data
        $stmt = $conn->prepare("SELECT c.id_client, c.name, c.prix, c.date, t.nomSoin AS typeSoin, COUNT(*) AS typeSoinCount
                               FROM client c
                               INNER JOIN appointments a ON c.id_client = a.id_client
                               INNER JOIN typeSoin t ON a.id_typeSoin = t.id_typeSoin
                               WHERE c.id_client = :id
                               GROUP BY c.id_client");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$appointment) {
            header("Location: view.php");
            exit();
        }

        // Calculate the total price
        $totalPrice = number_format($appointment['prix'] * $appointment['typeSoinCount'], 2, '.', '') . "DH";
        ?>

        <div class="bill-details">
            <h2>Bill Information</h2>
            <p><strong>Name:</strong> <?php echo $appointment['name']; ?></p>
            <p><strong>Type Soin:</strong> <?php echo $appointment['typeSoin']; ?></p>
            <p><strong>Date:</strong> <?php echo $appointment['date']; ?></p>
            <p><strong>Price:</strong> <?php echo $appointment['prix']; ?></p>
            <p><strong>Total Price:</strong> <?php echo $totalPrice; ?></p>
        </div>
        <a href="download_pdf.php?id=<?php echo $appointment['id_client']; ?>" class="btn btn-success">Download &nbsp <i class="fa fa-download"></i></a>

        <!-- <button onclick="window.print();" class="print-button btn btn-success">Print</button> -->
        <div class="img-container">
            <img src="images/qrcode.png" alt="Bottom Left">
            <img src="images/cachet.jpg" alt="Bottom Right">
        </div>
    </div>
</body>

</html>