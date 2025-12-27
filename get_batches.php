<?php
session_start();
include('includes/config.php');

// Ensure the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $c_code = $_POST['c_code'];
    $selected_batches = isset($_POST['selected_batches']) ? $_POST['selected_batches'] : [];

    if (!empty($c_code)) {
        // Fetch batches based on course code and exclude already selected batches
        $query = "SELECT b_code FROM batch WHERE c_code = '$c_code'";

        if (!empty($selected_batches)) {
            $placeholders = implode(',', array_fill(0, count($selected_batches), "'?'"));
            $query .= " AND b_code NOT IN ($placeholders)";

            // Combine the course code with the selected batches to include them in the query
            $query = vsprintf($query, array_merge([$c_code], $selected_batches));
        }

        // Run the query directly
        $result = mysqli_query($conn, $query);

        $batches = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $batches[] = $row;
            }
        }

        echo json_encode($batches);
        exit();
    }
}

echo json_encode([]);
exit();
?>
