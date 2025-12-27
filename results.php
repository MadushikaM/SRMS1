<?php
// Database connection
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results by NIC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            background-image: url(images/SLGTI1.jpg);
            background-size: cover;
        }
            
        .container {
            max-width: 600px;
            margin: 150px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8); /* White with 80% opacity */
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px; /* Optional: for rounded corners */
            
        }
        h2 {
            text-align: center;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        input[type="text"] {
            width: 70%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #45a049;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #EF476F;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Results by NIC</h2>
        <form method="post" action="view-results.php">
            <div><input type="text" id="search_key" name="search_key" placeholder="Enter Your NIC No" required></div>
            <div><button type="submit">Search</button></div>
        </form>
    </div>
</body>
</html>
