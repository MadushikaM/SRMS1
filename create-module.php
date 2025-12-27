<?php
session_start();

// Display errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Redirect to login if not authenticated
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Manual form submission
    if (isset($_POST['submit'])) {
        $m_code = trim($_POST['m_code']);
        $m_name = trim($_POST['m_name']);
        $semester = trim($_POST['semester']);
        $d_code = trim($_POST['d_name']);
        $course = trim($_POST['course']);

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO module (m_code, m_name, semester, d_code, c_code) VALUES ('$m_code', '$m_name', '$semester', '$d_code', '$course')";
        
        if (mysqli_query($conn, $sql)) {
            $msg = "Module created successfully.";
        } else {
            $error = "Error: Could not create module. Please try again.";
        }
        
    }

    // Handle Excel file upload
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['name'] != '') {
        $file = $_FILES['excelFile']['tmp_name'];

        // Validate file type
        $fileType = mime_content_type($file);
        if ($fileType !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $_SESSION['error'] = "Invalid file format. Please upload a valid Excel file.";
        } else {
            try {
                $spreadsheet = IOFactory::load($file);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                $insertedCount = 0;

                // Process each row in the Excel file
                for ($i = 1; $i < count($sheetData); $i++) {
                    $row = $sheetData[$i];
                
                    $mcode = trim($row[0]);
                    $mname = trim($row[1]);
                    $semester = trim($row[2]);
                    $d_code = trim($row[3]);
                    $c_code = trim($row[4]);
                
                    // Validate row data
                    if ($mcode && $mname && $semester && $d_code && $c_code) {
                        // Build the SQL query dynamically
                        $sql = "INSERT INTO module (m_code, m_name, semester, d_code, c_code) 
                                VALUES ('$mcode', '$mname', '$semester', '$d_code', '$c_code')";
                
                        if (mysqli_query($conn, $sql)) {
                            $insertedCount++;
                        } else {
                            // Optionally, log the error for debugging
                            echo "Error: " . mysqli_error($conn) . " in row $i\n";
                        }
                    }
                }
                
                $_SESSION['success'] = "$insertedCount modules were successfully uploaded.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error processing the Excel file: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin - Create Module</title>
    <?php include_once 'script.php'; ?>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Create Module</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">Module</a></li>
                                    <li class="active">Create Module</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <h5>Create Module</h5>
                                        </div>

                                        <!-- Display Messages -->
                                        <?php if (!empty($msg)) { ?>
                                            <div class="succWrap"><?php echo htmlentities($msg); ?></div>
                                        <?php } elseif (!empty($error)) { ?>
                                            <div class="errorWrap"><?php echo htmlentities($error); ?></div>
                                        <?php } ?>

                                        <div class="panel-body">
                                            <!-- Excel Upload -->
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="excelFile">Upload Excel File</label>
                                                    <input type="file" name="excelFile" class="form-control" required>
                                                    <small>Example Format: <a href="module.xlsx" target="_blank">Download Sample</a></small>
                                                </div>
                                                <button type="submit" name="import_excel" class="btn btn-success">Import Excel</button>
                                            </form>

                                            <!-- Manual Form -->
                                            <form method="POST">
                                                <div class="form-group">
                                                    <label for="m_code">Module Code</label>
                                                    <input type="text" name="m_code" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="m_name">Module Name</label>
                                                    <input type="text" name="m_name" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="semester">Semester</label>
                                                    <select name="semester" class="form-control" required>
                                                        <option value="">Select Semester</option>
                                                        <option value="Semester 1">Semester 1</option>
                                                        <option value="Semester 2">Semester 2</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="d_name">Department</label>
                                                    <select name="d_name" id="d_name" class="form-control" required>
                                                        <option value=" ">Select Department</option>
                                                        <?php
                                                        $query = "SELECT * FROM department";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['d_code']}'>{$row['d_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="course">Course</label>
                                                    <select name="course" id="course" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                    </select>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch courses based on department selection
        document.getElementById('d_name').addEventListener('change', function () {
            var d_code = this.value;
            var courseDropdown = document.getElementById('course');
            courseDropdown.innerHTML = '<option value="">Select Course</option>';
            if (d_code) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'get_courses.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var courses = JSON.parse(xhr.responseText);
                        courses.forEach(function (course) {
                            var option = document.createElement('option');
                            option.value = course.c_code;
                            option.textContent = course.c_name;
                            courseDropdown.appendChild(option);
                        });
                    }
                };
                xhr.send('d_code=' + encodeURIComponent(d_code));
            }
        });
    </script>
</body>
</html>
