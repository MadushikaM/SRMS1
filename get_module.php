<?php
include('includes/config.php');

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    $query = "SELECT * FROM module WHERE c_name = '$course_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>
                        <input type='hidden' name='module[]' value='{$row['id']}'>
                        <input type='hidden' name='m_code[]' value='{$row['m_code']}'>
                        {$row['m_name']}
                    </td>
                    <td>
                        <input type='date' name='date[]' class='form-control' required>
                    </td>
                    <td>
                        <input type='time' name='time[]' class='form-control' required>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No modules found for the selected course.</td></tr>";
    }
}
?>

