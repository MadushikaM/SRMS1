<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect if not logged in
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
} else {
    // Get the batch ID if it's set in the URL
    $batchid = isset($_GET['batchid']) ? intval($_GET['batchid']) : 0;

    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Sanitize inputs to prevent SQL injection
        $b_code = mysqli_real_escape_string($conn, $_POST['b_code']);
        $d_code = mysqli_real_escape_string($conn, $_POST['d_code']);
        $c_code = mysqli_real_escape_string($conn, $_POST['course']);
        $year = mysqli_real_escape_string($conn, $_POST['year']);
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

        if ($batchid > 0) {
            // Update existing batch
            $sql = "UPDATE batch SET b_code = '$b_code', d_code = '$d_code', c_code = '$c_code', year = '$year', start_date = '$start_date', end_date = '$end_date' WHERE id = $batchid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Batch updated successfully";
            } else {
                $error = "Something went wrong: " . mysqli_error($conn);
            }
        } else {
            // Insert new batch
            $sql = "INSERT INTO batch (b_code, d_code, c_code, year, start_date, end_date) VALUES ('$b_code', '$d_code', '$c_code', '$year', '$start_date', '$end_date')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Batch created successfully";
            } else {
                $error = "Something went wrong: " . mysqli_error($conn);
            }
        }
    }

    // Initialize form variables
    $b_code = "";
    $d_code = "";
    $course = "";
    $year = "";
    $start_date = "";
    $end_date = "";

    if ($batchid > 0) {
        // Fetch existing batch details for editing
        $query = "SELECT * FROM batch WHERE id = $batchid";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if ($row = mysqli_fetch_assoc($result)) {
                $b_code = $row['b_code'];
                $d_code = $row['d_code'];
                $course = $row['c_code'];
                $year = $row['year'];
                $start_date = $row['start_date'];
                $end_date = $row['end_date'];
            }
        } else {
            $error = "Failed to fetch batch details: " . mysqli_error($conn);
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
    <title>SMS Admin <?php echo $batchid > 0 ? "Update Batch" : "Create Batch"; ?></title>
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
            $("#d_code").change(function() {
                var d_code = $(this).val();
                if (d_code != "") {
                    $.ajax({
                        url: "get_courses.php",
                        method: "POST",
                        data: { d_code: d_code },
                        success: function(data) {
                            $("#course").html(data);
                        }
                    });
                } else {
                    $("#course").html("<option value=''>Select Course</option>");
                }
            });

            // Fetch next b_code when course is selected
            $("#course").change(function() {
                var c_code = $(this).val();
                var bCodeField = $("#b_code");
                if (c_code != "") {
                    $.ajax({
                        url: "get_next_bcode.php",
                        method: "POST",
                        data: { c_code: c_code },
                        success: function(data) {
                            var response = JSON.parse(data);
                            if (response.next_bcode) {
                                bCodeField.val(response.next_bcode);
                            } else {
                                bCodeField.val("");
                                alert("Error fetching the next Batch Code.");
                            }
                        }
                    });
                } else {
                    bCodeField.val("");
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
                            <h2 class="title"><?php echo $batchid > 0 ? "Update Batch" : "Create Batch"; ?></h2>
                        </div>
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                <li><a href="#">Batch</a></li>
                                <li class="active"><?php echo $batchid > 0 ? "Update Batch" : "Create Batch"; ?></li>
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
                                            <h5><?php echo $batchid > 0 ? "Update Batch" : "Create Batch"; ?></h5>
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
                                        <form method="POST">
                                            <div class="form-group">
                                                <label for="d_name" class="control-label">Department</label>
                                                <select name="d_code" id="d_name" class="form-control" required>
                                                    <option value="">Select Department</option>
                                                    <?php
                                                    $query = "SELECT * FROM department";
                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $selected = ($d_code == $row['d_code']) ? 'selected' : '';
                                                        echo "<option value='{$row['d_code']}' {$selected}>{$row['d_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="course" class="control-label">Course</label>
                                                <select name="course" id="course" class="form-control" required>
                                                    <option value="">Select Course</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="b_code" class="control-label">Batch Code</label>
                                                <input type="text" name="b_code" value="<?php echo htmlentities($b_code); ?>" class="form-control" required id="b_code" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="year" class="control-label">Year</label>
                                                <input type="text" name="year" value="<?php echo htmlentities($year); ?>" class="form-control" required id="year">
                                            </div>
                                            <div class="form-group">
                                                <label for="start_date" class="control-label">Start Date</label>
                                                <input type="date" name="start_date" value="<?php echo htmlentities($start_date); ?>" required class="form-control" id="start_date">
                                            </div>
                                            <div class="form-group">
                                                <label for="end_date" class="control-label">End Date</label>
                                                <input type="date" name="end_date" value="<?php echo htmlentities($end_date); ?>" required class="form-control" id="end_date">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="submit" class="btn btn-success btn-labeled">
                                                    <?php echo $batchid > 0 ? "Update" : "Submit"; ?>
                                                    <span class="btn-label btn-label-right"><i class="fa fa-check"></i></span>
                                                </button>
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
        <script src="js/jquery-ui/jquery-ui.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/main.js"></script>

        <script>
            document.getElementById('d_name').addEventListener('change', function() {
                var d_code = this.value;
                var courseDropdown = document.getElementById('course');
                courseDropdown.innerHTML = '<option value="">Select Course</option>';
                if (d_code) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'get_courses.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var courses = JSON.parse(xhr.responseText);
                            courses.forEach(function(course) {
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

            //next b_code
            document.getElementById('course').addEventListener('change', function() {
                var c_code = this.value;
                var bCodeField = document.getElementById('b_code');

                if (c_code) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'get_next_bcode.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.next_bcode) {
                                bCodeField.value = response.next_bcode;
                            } else {
                                bCodeField.value = ''; // Reset on error
                                alert('Error fetching the next Batch Code.');
                            }
                        }
                    };
                    xhr.send('c_code=' + encodeURIComponent(c_code));
                } else {
                    bCodeField.value = ''; // Clear field if no course is selected
                }
            });
        </script>

    </body>
</html>
