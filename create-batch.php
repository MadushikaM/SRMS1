<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $batchid = isset($_GET['batchid']) ? intval($_GET['batchid']) : 0;
    if (isset($_POST['submit'])) {
        $b_code = $_POST['b_code'];
        $d_name = $_POST['d_code'];
        $c_code = $_POST['c_code'];
        $year = $_POST['year'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        if ($batchid > 0) {
            $sql = "UPDATE batch SET b_code = '$b_code', d_code = '$d_name', c_code = '$c_code', year = '$year', start_date = '$start_date', end_date = '$end_date' WHERE id = $batchid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Batch updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            $sql = "INSERT INTO batch ( b_code, c_code, year, start_date, end_date) VALUES ('$b_code', '$c_code', '$year', '$start_date', '$end_date')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Batch created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    $b_code = "";
    $d_name = "";
    $course = "";
    $year = "";
    $start_date = "";
    $end_date = "";

    if ($batchid > 0) {
        $query = "SELECT * FROM batch WHERE id = $batchid";
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);
        $b_code = $row['b_code'];
        $course = $row['c_code'];
        $year = $row['year'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
    }

    // Fetch the rows to ensure consistent ordering (for example, order by ID)
    $sqlSelect = "SELECT id FROM batch ORDER BY id ASC";
    $result = $conn->query($sqlSelect);

    if ($result->num_rows > 0) {
        $b_code = 1; // Start from 1
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];

            // Update the b_code for the current row
            $sqlUpdate = "UPDATE batch SET b_code = $b_code WHERE id = $id";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo "";
            } else {
                echo " " . $conn->error . "<br>";
            }

            $b_code++; // Increment the counter
        }
    } else {
        echo "";
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
                $("#d_name").change(function() {
                    var d_code = $(this).val(); // Get selected department code
                    if (d_code != "") {
                        $.ajax({
                            url: "getco.php", // Fetch courses based on the department code
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
                                                        <label for="b_code" class="control-label">Batch Code</label>
                                                        <input type="text" name="b_code" value="<?php echo htmlentities($b_code); ?>" class="form-control" required="required" id="b_code">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="d_name" class="control-label">Department</label>
                                                        <select name="d_name" id="d_name" class="form-control" required="required">
                                                            <option value="">Select Department</option>
                                                            <?php
                                                            $query = "SELECT * FROM department";
                                                            $result = mysqli_query($conn, $query);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $selected = ($d_name == $row['d_name']) ? 'selected' : '';
                                                                echo "<option value='{$row['d_code']}' {$selected}>{$row['d_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="course" class="control-label">Course</label>
                                                        <select name="c_code" id="course" class="form-control" required="required">
                                                            <option value="">Course</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="year" class="control-label">Year</label>
                                                        <input type="text" name="year" value="<?php echo htmlentities($year); ?>" class="form-control" required="required" id="year">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="start_date" class="control-label">Start Date</label>
                                                        <input type="date" name="start_date" value="<?php echo htmlentities($start_date); ?>" required="required" class="form-control" id="start_date">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="end_date" class="control-label">End Date</label>
                                                        <input type="date" name="end_date" value="<?php echo htmlentities($end_date); ?>" required="required" class="form-control" id="end_date">
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" name="submit" class="btn btn-success btn-labeled"><?php echo $batchid > 0 ? "Update" : "Submit"; ?>
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

    </body>

    </html>
<?php } ?>