<?php
include('includes/config.php');

if (isset($_POST['d_code'])) {
    $d_code = $_POST['d_code'];

    $query = "SELECT * FROM course WHERE d_code = '$d_code'";
    $result = mysqli_query($conn, $query);

    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row; // Add each course row to the array
    }

    echo json_encode($courses); // Return as JSON response
}
?>
