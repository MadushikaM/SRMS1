<?php
// Include database configuration
include('includes/config.php');

// Check if the AJAX request contains the 'd_code' parameter
if (isset($_POST['d_code']) && !empty($_POST['d_code'])) {
    $d_code = $_POST['d_code'];

    // Prepare a query to fetch courses related to the selected department
    $query = "SELECT * FROM course WHERE d_code = ?";
    $stmt = $conn->prepare($query);

    // Bind the department code parameter
    $stmt->bind_param("s", $d_code);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if courses are available
    if ($result->num_rows > 0) {
        echo "<option value=''>Select Course</option>";
        while ($row = $result->fetch_assoc()) {
            // Display each course as an option
            echo "<option value='" . $row['c_code'] . "'>" . $row['c_name'] . "</option>";
        }
    } else {
        echo "<option value=''>No Courses Available</option>";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "<option value=''>Invalid Department</option>";
}
?>
