<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header("Location: index.php");
    exit();
}

$msg = "";
$error = "";

// Insert or update results
if (isset($_POST['submit_manual'])) {
    $exam_id = mysqli_real_escape_string($conn, $_POST['exam_id']);
    $s_id = mysqli_real_escape_string($conn, $_POST['s_id']);
    $marks = mysqli_real_escape_string($conn, $_POST['marks']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);

    // Insert operation for manual result submission
    $sql = "INSERT INTO results (exam_id, s_id, marks, grade) VALUES ('$exam_id', '$s_id', '$marks', '$grade')";
    if (mysqli_query($conn, $sql)) {
        $msg = "Manual viva result added successfully!";
    } else {
        $error = "Database error: " . mysqli_error($conn);
    }
}

// Handle CSV file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
    $allowedExtensions = ['csv'];
    $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if (in_array($fileExtension, $allowedExtensions)) {
        if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
            $file = fopen($_FILES["file"]["tmp_name"], "r");
            fgetcsv($file); // Skip header row

            while (($row = fgetcsv($file)) !== FALSE) {
                $exam_id = mysqli_real_escape_string($conn, $row[0]);
                $s_id = mysqli_real_escape_string($conn, $row[1]);
                $marks = mysqli_real_escape_string($conn, $row[2]);
                $grade = mysqli_real_escape_string($conn, $row[3]);

                $sql = "INSERT INTO results (exam_id, s_id, marks, grade) VALUES ('$exam_id', '$s_id', '$marks', '$grade')";
                if (!mysqli_query($conn, $sql)) {
                    $error = "Database error: " . mysqli_error($conn);
                    break;
                }
            }
            fclose($file);

            if (!$error) {
                $msg = "CSV uploaded successfully!";
            }
        } else {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "Invalid file type. Please upload a valid CSV file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Add Results</title>
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
    <script>
        $(document).ready(function () {
            $("#d_name").change(function () {
                var d_code = $(this).val();
                if (d_code != "") {
                    $.ajax({
                        url: "getco.php",
                        method: "POST",
                        data: {d_code: d_code},
                        success: function (data) {
                            $("#course").html(data);
                            $("#upload-section").hide();
                        }
                    });
                } else {
                    $("#course").html("<option value=''>Select Course</option>");
                }
            });

            $("#course").change(function () {
                var c_code = $(this).val();
                if (c_code != "") {
                    $("#upload-section").show();
                } else {
                    $("#upload-section").hide();
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
                            <h2 class="title">Add Results</h2>
                        </div>
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                <li><a href="#">Results</a></li>
                                <li class="active">Add Results</li>
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
                                            <h5>Add Results</h5>
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
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="d_name">Department</label>
                                                <select name="d_name" id="d_name" class="form-control" required>
                                                    <option value="">Select Department</option>
                                                    <?php
                                                    $query = "SELECT * FROM department";
                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . $row['d_code'] . "'>" . $row['d_name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="course">Course</label>
                                                <select name="c_code" id="course" class="form-control" required>
                                                    <option value="">Select Course</option>
                                                </select>
                                            </div>

                                            <div class="form-group" id="upload-section" style="display:none;">
                                                <label for="file">Upload CSV File</label>
                                                <input type="file" name="file" class="form-control" accept=".csv" required>
                                                <small>Example Format: <a href="sample_format.csv" target="_blank">Download Sample</a></small>
                                                <button type="submit" class="btn btn-success">Upload</button>
                                            </div>
                                        </form>

                                        <hr>

                                        <h5>Manual Viva Result Submission</h5>
                                        <form method="POST">
                                            <div class="form-group">
                                                <label for="exam_id">Exam ID</label>
                                                <select name="exam_id" class="form-control" required>
                                                    <option value="">Select Exam</option>
                                                    <?php
                                                    $query = "SELECT exam_id FROM exam";
                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . $row['exam_id'] . "'>" . $row['exam_id'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="s_id">Student ID</label>
                                                <input type="text" name="s_id" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="marks">Marks</label>
                                                <input type="number" name="marks" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="grade">Grade</label>
                                                <select name="grade" class="form-control" required>
                                                    <option value="">Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="submit_manual" class="btn btn-primary">Submit</button>
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
<script src="js/main.js"></script>
</body>
</html>
