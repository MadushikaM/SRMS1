<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
} else {
    $batchid = isset($_GET['batchid']) ? intval($_GET['batchid']) : 0;
    $msg = "";
    $error = "";

    // Initial variables
    $b_code = "";
    $d_name = "";
    $c_code = "";
    $year = "";
    $start_date = "";
    $end_date = "";

    // Fetch batch code based on department and course
    if ($batchid == 0) {
        $b_code = 1; // Default batch code is 1 if no batch exists

        if (isset($_POST['d_name']) && isset($_POST['course'])) {
            $d_name = $_POST['d_name'];
            $c_code = $_POST['course'];

            // Get the highest batch code for the selected course
            $result = mysqli_query($conn, "SELECT MAX(b_code) AS max_code FROM batch WHERE c_code = '$c_code'");
            $row = mysqli_fetch_assoc($result);
            $b_code = $row['max_code'] ? $row['max_code'] + 1 : 1; // Increment by 1 if batch code exists, else default to 1
        }
    } else {
        // Fetch batch details if updating
        $sql = "SELECT * FROM batch WHERE id = $batchid";
        $result = mysqli_query($conn, $sql);
        $batch = mysqli_fetch_assoc($result);
        $d_name = $batch['d_code'];
        $c_code = $batch['c_code'];
        $year = $batch['year'];
        $start_date = $batch['start_date'];
        $end_date = $batch['end_date'];
    }

    if (isset($_POST['submit'])) {
        $d_name = $_POST['d_name'];
        $c_code = $_POST['course'];
        $year = $_POST['year'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        if ($batchid > 0) {
            // Update operation
            $sql = "UPDATE batch 
                    SET d_code = '$d_name', c_code = '$c_code', year = '$year', start_date = '$start_date', end_date = '$end_date' 
                    WHERE id = $batchid";
            if (mysqli_query($conn, $sql)) {
                $msg = "Batch updated successfully";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } else {
            // Insert operation for a new batch
            $sql = "INSERT INTO batch (`b_code`, `c_code`, `year`, `start_date`, `end_date`) 
                    VALUES ('$b_code', '$d_name', '$c_code', '$year', '$start_date', '$end_date')";
            if (mysqli_query($conn, $sql)) {
                $msg = "Batch created successfully";
            } else {
                $error = "Something went wrong. Please try again.";
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
                                                    <select name="d_name" id="d_name" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php
                                                        $query = "SELECT * FROM department";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $selected = ($d_name == $row['d_code']) ? 'selected' : '';
                                                            echo "<option value='{$row['d_code']}' {$selected}>{$row['d_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="course" class="control-label">Course</label>
                                                    <select name="course" id="course" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                        <?php
                                                        if ($d_name) {
                                                            $query = "SELECT * FROM course WHERE d_code = '$d_name'";
                                                            $result = mysqli_query($conn, $query);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $selected = ($c_code == $row['c_code']) ? 'selected' : '';
                                                                echo "<option value='{$row['c_code']}' {$selected}>{$row['c_name']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <?php if ($batchid == 0) { ?>
                                                    <div class="form-group">
                                                        <label for="b_code" class="control-label">Batch Code (Auto-generated)</label>
                                                        <input type="text" name="b_code" value="<?php echo $b_code; ?>" class="form-control" readonly>
                                                    </div>
                                                <?php } ?>
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
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>
    <script src="js/prism/prism.js"></script>
    <script src="js/main.js"></script>
</body>

</html>

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
</script>

</body>

</html>
<?php } ?>
