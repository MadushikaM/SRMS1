<?php
include('includes/config.php');

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Query to fetch batches based on the selected course
    $query = "SELECT b_code FROM batch WHERE c_code = '$course_id'";
    $result = mysqli_query($conn, $query);

    // Check if query executed successfully
    if ($result) {
        // Generate options for the dropdown
        echo "<option value=''>Select Batch</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['b_code'] . "'>" . $row['b_name'] . " (" . $row['b_code'] . ")</option>";
        }
    } else {
        echo "<option value=''>Error fetching batches</option>";
    }

    // Close database connection
    mysqli_close($conn);
}
?>
