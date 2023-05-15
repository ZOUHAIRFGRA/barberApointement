<?php

require('connection.php');
require('header.php');

// Fetch the statistics data
$stmt = $conn->prepare("SELECT typeSoin.nomSoin, COUNT(appointments.id_appointment) AS appointmentCount
                       FROM typeSoin
                       LEFT JOIN appointments ON typeSoin.id_typeSoin = appointments.id_typeSoin
                       GROUP BY typeSoin.nomSoin");
$stmt->execute();
$statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Statistics</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Appointment Statistics</h2>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Type Soin</th>
                    <th>Appointment Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statistics as $row) : ?>
                    <tr>
                        <td><?php echo $row['nomSoin']; ?></td>
                        <td><?php echo $row['appointmentCount']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center">
            <a href="Graph_Appointements.php" class="btn btn-primary mt-4">Graph</a>
        </div>
    </div>
</body>

</html>
<?php ?>