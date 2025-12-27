<?php
include('includes/config.php');

if (isset($_POST['c_code'])) {
    $c_code = $_POST['c_code'];

    $query = "SELECT * FROM module WHERE c_code = '$c_code'";
    $result = mysqli_query($conn, $query);

    $modules = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $modules[] = $row;
    }

    echo json_encode($modules);
}
?>
