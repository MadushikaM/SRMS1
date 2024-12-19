<?php
include('includes/config.php');

if (isset($_POST['b_code'])) {
    $batch_id = $_POST['b_code'];

    $query = "SELECT * FROM module WHERE b_id = '$batch_id'";
    $result = mysqli_query($conn, $query);

    $modules = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $modules[] = $row;
    }

    echo json_encode($modules);
}
?>
