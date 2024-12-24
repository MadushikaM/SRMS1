<?php
// Database connection
include('includes/config.php');

// Get the NIC from the form
$search_key = isset($_POST['search_key']) ? $_POST['search_key'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($search_key)) {
    // Sanitize user input to prevent SQL injection
    $search_key = mysqli_real_escape_string($conn, $search_key);

    // Query to fetch results based on the NIC
    $query = "
        SELECT 
            s.fullname, 
            s.nic, 
            s.index_no, 
            c.c_code, 
            c.c_name, 
            m.semester, 
            m.m_name AS module_name, 
            r.marks, 
            r.grade 
        FROM 
            student s
        INNER JOIN 
            results r ON s.id = r.s_id
        INNER JOIN 
            exam e ON r.exam_id = e.exam_id
        INNER JOIN 
            module m ON e.module = m.m_code
        INNER JOIN 
            course c ON m.c_name = c.c_name
        WHERE 
            s.nic = '$search_key'
        ORDER BY 
            m.semester ASC, m.m_name ASC
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Display results
            echo "<h2>Student Results</h2>";
            echo "<table border='1'>";
            echo "<tr>
                    <th>Full Name</th>
                    <th>NIC</th>
                    <th>Index No</th>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Semester</th>
                    <th>Module</th>
                    <th>Marks</th>
                    <th>Grade</th>
                  </tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['fullname'] . "</td>";
                echo "<td>" . $row['nic'] . "</td>";
                echo "<td>" . $row['index_no'] . "</td>";
                echo "<td>" . $row['c_code'] . "</td>";
                echo "<td>" . $row['c_name'] . "</td>";
                echo "<td>" . $row['semester'] . "</td>";
                echo "<td>" . $row['module_name'] . "</td>";
                echo "<td>" . $row['marks'] . "</td>";
                echo "<td>" . $row['grade'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Print button
            echo "<button onclick='window.print()'>Print</button>";
        } else {
            echo "No results found for the provided NIC.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Results</title>
</head>
<body>
    <h2>Search Results by NIC</h2>
    <form method="post" action="">
        <label for="search_key">NIC:</label>
        <input type="text" id="search_key" name="search_key" required>
        <button type="submit">Search</button>
    </form>
</body>
</html>
