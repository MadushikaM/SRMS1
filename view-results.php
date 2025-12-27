<?php
include_once 'includes/config.php'; // Include your database configuration file

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
            c.c_name, 
            m.semester, 
            m.m_name AS module_name, 
            r.marks, 
            r.grade 
        FROM 
            student s
        INNER JOIN 
            results r ON s.nic = r.nic
        INNER JOIN  
            exam e ON r.exam_id = e.exam_id
        INNER JOIN 
            module m ON e.module = m.id
        INNER JOIN 
            course c ON m.c_code = c.c_code
        WHERE 
            s.nic = '$search_key' and e.status='approved'
        ORDER BY 
            m.semester ASC, m.m_name ASC
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Fetch the first row to display student details
            $row = mysqli_fetch_assoc($result);
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Results</title>
                <style>
                    body {
                        font-family: 'Times New Roman', Times, serif, sans-serif;
                        background-color: #f9f9f9;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 800px;
                        margin: 20px auto;
                        padding: 20px;
                        background: #fff;
                        border: 1px solid #ccc;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        text-align: center;
                    }
                    p {
                        font-size: 16px;
                        margin: 5px 0;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table, th, td {
                        border: 1px solid #ccc;
                    }
                    th, td {
                        padding: 10px;
                        text-align: center;
                    }
                    th {
                        background: #f4f4f4;
                    }
                    .print-btn {
                        margin: 20px 0;
                        text-align: center;
                        
                    }
                    .print-btn button {
                        padding: 10px 20px;
                        background-color: #4CAF50;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                        font-size: 16px;
                        border-radius: 5px;
                    }
                    .print-btn button:hover {
                        background-color: #ED0E23;
                    }
                </style>
                <script>
                    function printResults() {
                        window.print();
                    }
                </script>
            </head>
            <body>
                <div class="container">
                    <img src="images/SLGTI.png" alt="Uploaded Image" style="width:800px; height:150px;">
                    <h1>RESULTS</h1>
                    <p><strong>FULL NAME:</strong> <?php echo $row['fullname']; ?></p>
                    <p><strong>INDEX NO:</strong> <?php echo $row['index_no']; ?></p>
                    <p><strong>NIC:</strong> <?php echo $row['nic']; ?></p>
                    <p><strong>COURSE NAME:</strong> <?php echo $row['c_name']; ?></p>
                    <p><strong>SEMESTER:</strong> <?php echo $row['semester']; ?></p>
                    <br>

                    <!-- Results Table -->
                    <table>
                        <tr>
                            <th>SUBJECT</th>
                            <th>MARKS</th>
                            <th>GRADE</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['module_name']; ?></td>
                            <td><?php echo $row['marks']; ?></td>
                            <td><?php echo $row['grade']; ?></td>
                        </tr>
                        <?php
                        // Fetch and display the remaining rows
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['module_name']}</td>
                                    <td>{$row['marks']}</td>
                                    <td>{$row['grade']}</td>
                                </tr>";
                        }
                        ?>
                    </table>

                    <!-- Print Button -->
                    <div class="print-btn">
                        <button onclick="printResults()">Print</button>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "<p style='color: red; text-align: center;'>No results found for the provided NIC.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);
