<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['c_code'])) {
    $c_code = $_POST['c_code'];

    // Query to find the maximum b_code for the selected course
    $query = "SELECT MAX(b_code) AS max_bcode FROM batch WHERE c_code = '$c_code'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $next_bcode = $row['max_bcode'] ? intval($row['max_bcode']) + 1 : 1;
        echo json_encode(['next_bcode' => $next_bcode]);
    } else {
        echo json_encode(['error' => 'Error fetching next b_code']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
