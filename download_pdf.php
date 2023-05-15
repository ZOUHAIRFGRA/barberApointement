
<?php

require_once('fpdf/fpdf.php');
require('connection.php');

$id = $_GET['id'];

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('images\cmc.png', 10, 6, 30);
        $this->Image('images\logoSalon.png', 180, 6, 30);
        $this->Image('images\cachet.jpg', 6, 260, 30);
        $this->Image('images\qrcode.png', 160, 250, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the center of the page
        $this->Cell(0, 30, '', 0, 1, 'C');
        // Title
        $this->Cell(0, 10, 'Appointment Bill', 0, 1, 'C');
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Fetch appointment data
$stmt = $conn->prepare("SELECT c.id_client, c.name, c.prix, c.date, GROUP_CONCAT(t.nomSoin SEPARATOR ', ') AS typesoin, COUNT(t.id_typeSoin) AS typeSoinCount
                       FROM client c
                       INNER JOIN appointments a ON c.id_client = a.id_client
                       INNER JOIN typeSoin t ON a.id_typeSoin = t.id_typeSoin
                       WHERE c.id_client = :id
                       GROUP BY c.id_client");
$stmt->bindParam(':id', $id);
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    exit("Appointment not found");
}

// Create PDF instance
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(0, 10, 'Appointment Details', 0, 1, 'C', true);

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(40, 10, 'Name:', 0, 0);
$pdf->Cell(0, 10, $appointment['name'], 0, 1);

$pdf->Cell(40, 10, 'Date:', 0, 0);
$pdf->Cell(0, 10, $appointment['date'], 0, 1);

$pdf->Cell(40, 10, 'Type of Service:', 0, 0);
$pdf->Cell(0, 10, $appointment['typesoin'], 0, 1);

$pdf->Cell(40, 10, 'Price:', 0, 0);
$pdf->Cell(0, 10, $appointment['prix'], 0, 1);

$pdf->Cell(40, 10, 'Total Price:', 0, 0);
$pdf->Cell(0, 10, number_format($appointment['prix'] * $appointment['typeSoinCount'], 2), 0, 1);

// Output the PDF
$pdf->Output();

?>