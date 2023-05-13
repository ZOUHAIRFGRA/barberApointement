<?php
session_start();
if (isset($_SESSION['password'])) {

?>
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

// Prepare the data for the graph
$labels = [];
$data = [];

foreach ($statistics as $row) {
    $labels[] = $row['nomSoin'];
    $data[] = $row['appointmentCount'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Appointment Graph</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">Appointment Graph</h2>
        <canvas id="appointmentsChart"></canvas>
    </div>

    <script>
        // Get the data from PHP
        var labels = <?php echo json_encode($labels); ?>;
        var data = <?php echo json_encode($data); ?>;

        // Create the chart
        var ctx = document.getElementById('appointmentsChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Appointment Count',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
</body>

</html><?php } else {
    header('location:login.php');
} ?>