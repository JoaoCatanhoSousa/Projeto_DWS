<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="/Projeto_DWS/Public/style/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" title="Go back to the login">Catanho's Hotel</a>
            <?php if (basename($_SERVER['PHP_SELF']) != 'logIn.php' && basename($_SERVER['PHP_SELF']) != 'signUp.php'): ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contactUs.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="terms.php">Terms</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotelReservation.php">Reservation</a></li>
                </ul>
            </div>
            <form method="post" action="settings.php" style="display:inline;">
                <button type="submit" class="custom-button1" title="Settings">
                    <span class="material-symbols-outlined">account_circle</span>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </nav>
    
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>