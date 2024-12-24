<?php
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Result Management System</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<style>
    /* css for view button */
    .form-container {
        position: absolute;
        bottom: 20px; /* Position the button 20px from the bottom */
        left: 50%; /* Center horizontally */
        transform: translateX(-50%);
        text-align: center;
    }

    .view-btn {
        padding: 10px 20px;
        font-size: 16px;
        color: #ffffff;
        background: linear-gradient(to right, #04255a,#0588c0,#ddc9ff);;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: box-shadow 0.3s ease-in-out;
        width: 200px;
        height: 50px;
    }

    .view-btn:hover {
        box-shadow: 0 0 15px #00bfff, 0 0 30px #00bfff;
    }

    /* body css*/
    body{
        background-image: url('images/Education.png') ;
        background-repeat: no-repeat;
        background-size: cover; 
    }
</style>


<body >
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #1a2d42;">
        <div class="container">
            <a class="navbar-brand" href="index.php" align="center" >Student Result Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="admin-login.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header - set the background image for the header in the line below-->
   

 <div class="form-container">
    
 <div class="form-container">
    <button class="view-btn" onclick="navigateToResults()">View Results</button>
</div>

<script>
    function navigateToResults() {
        window.location.href = "results.php";
    }
</script>


    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>