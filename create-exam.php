<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect to login if session not set
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $batches = $_POST['batch'];
    $modules = $_POST['module'];
    $dates = $_POST['date'];
    $times = $_POST['time'];

    $success = true;

    foreach ($modules as $index => $moduleId) {
        $batchId = $batches[$index];
        $date = $dates[$index];
        $time = $times[$index];

        $sql = "INSERT INTO exam (module, batch, date, time) VALUES ('$moduleId', '$batchId', '$date', '$time')";
        if (!mysqli_query($conn, $sql)) {
            $success = false;
        }
    }

    if ($success) {
        $msg = "Exams created successfully!";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Create Exam</title>
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
                                <h2 class="title">Create Exam</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">Exam</a></li>
                                    <li class="active">Create Exam</li>
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
                                            <h5>Create Exam</h5>
                                        </div>
                                        <?php if ($msg) { ?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } elseif ($error) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="panel-body">
                                            <form method="POST">
                                                <!-- Select Department -->
                                                <div class="form-group">
                                                    <label for="department">Department</label>
                                                    <select name="department" id="department" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php
                                                        $query = "SELECT * FROM department";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['d_code']}'>{$row['d_name']} ({$row['d_code']})</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Select Course -->
                                                <div class="form-group">
                                                    <label for="course">Course</label>
                                                    <select name="course" id="course" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                    </select>
                                                </div>

                                                <!-- Select Batch -->
                                                <div class="form-group">
                                                    <label for="batch">Batch</label>
                                                    <select name="b_code" id="b_code" class="form-control"  required>
                                                        <option value="">Select batch</option>
                                                        <?php
                                                        $query = "SELECT * FROM batch";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['b_code']}'>{$row['b_name']} {$row['b_code']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    </select>
                                                </div>

                                                <!-- Table for Modules -->
                                                <div class="form-group">
                                                    <label>Modules</label>
                                                    <table class="table table-bordered" id="moduleTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Module</th>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
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
    <script>
        $(document).ready(function() {
            // When a department is selected, load courses related to that department
            $("#department").change(function() {
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

        $(document).ready(function() {
            // Load batches when a course is selected
            $("#course").change(function() {
                var course_id = $(this).val(); // Get selected course ID
                if (course_id != "") {
                    $.ajax({
                        url: "get_batch.php", // Fetch batches based on course
                        method: "POST",
                        data: {
                            course_id: course_id
                        },
                        success: function(data) {
                            $("#batch").html(data); // Populate the batch dropdown
                        },
                    });

                    // Fetch modules based on course and populate the table
                    $.ajax({
                        url: "get_module.php",
                        method: "POST",
                        data: {
                            course_id: course_id
                        },
                        success: function(data) {
                            $("#moduleTable tbody").html(data); // Populate module table
                        },
                    });
                } else {
                    $("#batch").html("<option value=''>Select Batch</option>");
                    $("#moduleTable tbody").empty(); // Clear table
                }
            });
        });
    </script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>
    <script src="js/prism/prism.js"></script>
    <script src="js/main.js"></script>
</body>

</html>