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

// Fetch all typeSoins
$typeSoinStmt = $conn->prepare("SELECT * FROM typeSoin");
$typeSoinStmt->execute();
$typeSoins = $typeSoinStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch selected typeSoins for the appointment
$selectedTypeSoinsStmt = $conn->prepare("SELECT id_typeSoin FROM appointments WHERE id_client = :clientId");
$selectedTypeSoinsStmt->bindParam(':clientId', $appointmentId);
$selectedTypeSoinsStmt->execute();
$selectedTypeSoins = $selectedTypeSoinsStmt->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $selectedTypeSoins = $_POST['typeSoin']; // Array of selected checkboxes

    // Update the appointment record
    $stmt = $conn->prepare("UPDATE client SET name = :name, prix = :prix, date = :date WHERE id_client = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':id', $appointmentId);
    $stmt->execute();

    // Delete existing typeSoins for the appointment
    $deleteStmt = $conn->prepare("DELETE FROM appointments WHERE id_client = :clientId");
    $deleteStmt->bindParam(':clientId', $appointmentId);
    $deleteStmt->execute();

    // Insert updated typeSoins for the appointment
    $insertStmt = $conn->prepare("INSERT INTO appointments (id_client, id_typeSoin) VALUES (:clientId, :typeSoin)");
    foreach ($selectedTypeSoins as $typeSoin) {
        $insertStmt->bindParam(':clientId', $appointmentId);
        $insertStmt->bindParam(':typeSoin', $typeSoin);
        $insertStmt->execute();
    }

    // Redirect back to the view page
    header("Location: view.php");
    exit();
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html>

<head>
    <title>Update Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Update Appointment</h2>
        <form method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $appointment['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix:</label>
                <input type="text" class="form-control" id="prix" name="prix" value="<?php echo $appointment['prix']; ?>" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo $appointment['date']; ?>" required>
            </div>
            <div class="form-group">
                <label>Type Soin:</label>
                <div>
                    <?php foreach ($typeSoins as $typeSoin) { ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="typeSoin[]" value="<?php echo $typeSoin['id_typeSoin']; ?>" <?php if (in_array($typeSoin['id_typeSoin'], $selectedTypeSoins)) echo 'checked'; ?>>
                            <label class="form-check-label"><?php echo $typeSoin['nomSoin']; ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

</body>

</html>