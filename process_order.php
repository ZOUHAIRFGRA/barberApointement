<?php
require('connection.php');

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $selectedTypeSoins = $_POST['typeSoin']; // Array of selected checkboxes

    // Prepare the SQL statement for inserting into the client table
    $stmt = $conn->prepare("INSERT INTO client (name, prix, date) VALUES (:name, :prix, :date)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':date', $date);

    // Execute the statement
    $stmt->execute();

    // Get the inserted client ID
    $clientId = $conn->lastInsertId();

    // Prepare the SQL statement for inserting into the appointments table
    $stmt = $conn->prepare("INSERT INTO appointments (id_client, id_typeSoin) VALUES (:clientId, :typeSoin)");

    // Insert multiple rows for each selected typeSoin
    foreach ($selectedTypeSoins as $typeSoin) {
        // Get the typeSoin ID from the typeSoin table
        $typeSoinIdStmt = $conn->prepare("SELECT id_typeSoin FROM typeSoin WHERE nomSoin = :nomSoin");
        $typeSoinIdStmt->bindParam(':nomSoin', $typeSoin);
        $typeSoinIdStmt->execute();
        $typeSoinId = $typeSoinIdStmt->fetchColumn();

        // Insert into appointments table
        $stmt->bindParam(':clientId', $clientId);
        $stmt->bindParam(':typeSoin', $typeSoinId);
        $stmt->execute();
        
    }


    // Display an alert message
    echo "<script>alert('Registration succeeded');</script>";

    // Redirect back to the form page
    header("Location: index.php");
    exit();
}
