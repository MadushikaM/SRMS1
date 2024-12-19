<?php
include('includes/config.php');

if (isset($_POST['department'])) {
    $department = $_POST['department'];
    $query = "SELECT * FROM course WHERE department = '$department'";
    $result = mysqli_query($conn, $query);
    echo '<option value="">Select Course</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['c_name']}'>{$row['c_name']}</option>";
    }
}
?>
