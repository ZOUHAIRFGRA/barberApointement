<?php
require('header.php');
require('connection.php');

// Fetch appointments data
$stmt = $conn->prepare("SELECT c.id_client, c.name, c.prix, c.date, GROUP_CONCAT(t.nomSoin SEPARATOR ', ') AS typesoin
                       FROM client c
                       INNER JOIN appointments a ON c.id_client = a.id_client
                       INNER JOIN typeSoin t ON a.id_typeSoin = t.id_typeSoin
                       GROUP BY c.id_client");

$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Appointments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Appointments</h2>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Prix</th>
                    <th>Date</th>
                    <th>Type Soin</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) { ?>
                    <tr>
                        <td><?php echo $appointment['id_client']; ?></td>
                        <td><?php echo $appointment['name']; ?></td>
                        <td><?php echo $appointment['prix']; ?></td>
                        <td><?php echo $appointment['date']; ?></td>
                        <td><?php echo $appointment['typesoin']; ?></td>
                        <td>
                            <a href="update.php?id=<?php echo $appointment['id_client']; ?>" class="btn btn-primary">Update</a>
                            <a href="delete.php?id=<?php echo $appointment['id_client']; ?>" class="btn btn-danger">Delette</a>
                            <a href="bill.php?id=<?php echo $appointment['id_client']; ?>" class="btn btn-info">Generate pdf</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>