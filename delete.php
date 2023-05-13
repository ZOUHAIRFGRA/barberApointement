<?php
session_start();
if (isset($_SESSION['password'])) {

?>
<?php

require('connection.php');
require('header.php');

// Check if appointment ID is provided
if (!isset($_GET['id'])) {
    header("Location: view.php");
    exit();
}

$appointmentId = $_GET['id'];

// Fetch appointment data
$stmt = $conn->prepare("SELECT * FROM client WHERE id_client = :id");
$stmt->bindParam(':id', $appointmentId);
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    header("Location: view.php");
    exit();
}

// Handle the delete action
// Handle the delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the associated appointments first
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id_client = :id");
    $stmt->bindParam(':id', $appointmentId);
    $stmt->execute();

    // Delete the client record
    $stmt = $conn->prepare("DELETE FROM client WHERE id_client = :id");
    $stmt->bindParam(':id', $appointmentId);
    $stmt->execute();

    // Redirect back to the view page
    header("Location: view.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Delete Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Delete Appointment</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Prix</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $appointment['id_client']; ?></td>
                    <td><?php echo $appointment['name']; ?></td>
                    <td><?php echo $appointment['prix']; ?></td>
                    <td><?php echo $appointment['date']; ?></td>
                </tr>
            </tbody>
        </table>
        <p class="text-center">Are you sure you want to delete this appointment?</p>
        <form method="post" class="text-center">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="view.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html><?php } else {
    header('location:login.php');
} ?>