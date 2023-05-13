<?php
session_start();
if (isset($_SESSION['password'])) {

?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Salon Coiffeur</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <?php require('header.php') ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-5">
                    <h2 class="text-center mb-4">Salon Appointment</h2>
                    <form method="post" action="process_order.php">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Type Soin:</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="soinVisageCheckbox" name="typeSoin[]" value="soin visage">
                                    <label class="form-check-label" for="soinVisageCheckbox">Soin visage</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vernisCheckbox" name="typeSoin[]" value="vernis">
                                    <label class="form-check-label" for="vernisCheckbox">Vernis</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="chauveauCheckbox" name="typeSoin[]" value="chauveau">
                                    <label class="form-check-label" for="chauveauCheckbox">Chauveau</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="prix">Price:</label>
                            <input type="text" class="form-control" id="prix" name="prix" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date Soin:</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </html>
<?php

} else {
    header('location:login.php');
} ?>