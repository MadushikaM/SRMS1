<?php
session_start();
error_reporting(0);
include('includes/config.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    if (isset($_POST['submit'])) {
        // Manual form submission
        $m_code = $_POST['m_code']; // Module code
        $m_name = $_POST['m_name']; // Module name
        $semi = $_POST['semester'];
        $dname = $_POST['d_name']; // Department ID
        $cname = $_POST['course']; // Course name

        // Insert the module into the modules table
        $sql = "INSERT INTO module(m_code, m_name, semester, d_code, c_name) VALUES ('$m_code', '$m_name', '$semi', '$dname' , '$cname')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $msg = "Module Created successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }

    if (isset($_POST['import_excel'])) {
        // Excel file upload
        $file = $_FILES['file']['tmp_name'];
        if ($_FILES['file']['name'] != "") {
            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Loop through the rows and insert into database
                for ($row = 2; $row <= $highestRow; $row++) {
                    $m_code = $sheet->getCell('A' . $row)->getValue();
                    $m_name = $sheet->getCell('B' . $row)->getValue();
                    $semester = $sheet->getCell('C' . $row)->getValue();
                    $d_code = $sheet->getCell('D' . $row)->getValue();
                    $c_name = $sheet->getCell('E' . $row)->getValue();

                    $sql = "INSERT INTO module (m_code, m_name, semester, d_code, c_name) 
                            VALUES ('$m_code', '$m_name', '$semester', '$d_code', '$c_name')";
                    $result = mysqli_query($conn, $sql);
                    if (!$result) {
                        throw new Exception("Error inserting row $row");
                    }
                }
                $msg = "Modules imported successfully";
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        } else {
            $error = "Please select an Excel file to upload.";
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
    <title>SMS Admin Create Module</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // When a department is selected, load courses related to that department
            $("#d_name").change(function() {
                var d_code = $(this).val(); // Get selected department code
                if (d_code != "") {
                    $.ajax({
                        url: "get_courses.php", // Fetch courses based on the department code
                        method: "POST",
                        data: {
                            d_code: d_code
                        },
                        success: function(data) {
                            $("#course").html(data); // Populate the course dropdown
                        }
                    });
                } else {
                    $("#course").html("<option value=''>Select Course</option>");
                }
            });
        });
    </script>
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
                                            <div class="panel-title">
                                                <h5>Create Module</h5>
                                            </div>
                                        </div>

                                        <?php if ($msg) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } else if ($error) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>

                                        <div class="panel-body">
                                            <!-- Manual Entry Form -->
                                            <form method="POST">
                                                <div class="form-group">
                                                    <label for="m_code">Module Code</label>
                                                    <input type="text" name="m_code" class="form-control" required id="m_code">
                                                </div>
                                                <div class="form-group">
                                                    <label for="m_name">Module Name</label>
                                                    <input type="text" name="m_name" class="form-control" required id="m_name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="semester">Semester</label>
                                                    <select name="semester" id="semester" class="form-control" required>
                                                        <option value="">Select Semester</option>
                                                        <option value="Semester 1">Semester 1</option>
                                                        <option value="Semester 2">Semester 2</option>
                                                        <option value="Semester 3">Semester 3</option>
                                                        <option value="Semester 4">Semester 4</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="d_name">Department</label>
                                                    <select name="d_name" id="d_name" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php
                                                        $sql = "SELECT * FROM department ORDER BY d_name";
                                                        $result = mysqli_query($conn, $sql);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='" . $row['d_code'] . "'>" . $row['d_name'] . "</option>";
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
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </form>

                                            <!-- Excel File Upload Form -->
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="file">Upload Excel File</label>
                                                    <input type="file" name="file" id="file" class="form-control" accept=".xls,.xlsx">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="import_excel" class="btn btn-primary">Import Excel</button>
                                                </div>
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


    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>
    <script src="js/prism/prism.js"></script>
    <script src="js/select2/select2.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>