<?php
// Database connection
include('includes/config.php');

// Check if NIC is provided
if (isset($_POST['nic'])) {
    $nic = $_POST['nic'];

    // Fetch student details
    $query = "SELECT s.fullname, s.index_no, s.nic, s.course AS c_code, c.c_name, c.c_code, 
               m.semester, d.d_name, r.marks, r.grade FROM student s
        JOIN course c ON c.c_code = s.course
        JOIN department d ON d.d_code = c.d_code
        LEFT JOIN results r ON r.s_id = s.id
        LEFT JOIN module m ON m.c_name = c.c_name AND m.d_code = d.d_code
        WHERE s.nic = '$nic'
    ";

    $result = mysqli_query($dbh, $query);

    // Check if data is found
    if (mysqli_num_rows($result) > 0) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data found for this NIC']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'NIC not provided']);
}
?>
