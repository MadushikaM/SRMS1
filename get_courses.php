<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d_code = $_POST['d_code'];
    $query = "SELECT * FROM course WHERE d_code = '$d_code'";
    $result = mysqli_query($conn, $query);

    $options = "<option value=''>Select Course</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='{$row['id']}'>{$row['c_name']} ({$row['c_code']})</option>";
    }
    echo $options;
}
?>
