<?php
include('includes/config.php');

if (isset($_POST['c_code']) && isset($_POST['d_code'])) {
    $course_id = $_POST['c_code'];
    $department_code = $_POST['d_code'];

    $query = "SELECT * FROM batch WHERE c_code = '$course_id' AND d_code = '$department_code'";
    $result = mysqli_query($conn, $query);

    $batches = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $batches[] = $row;
    }

    echo json_encode($batches);
}
?>
