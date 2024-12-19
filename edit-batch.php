<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
}

$batchid = isset($_GET['batchid']) ? intval($_GET['batchid']) : 0;

// Initialize variables
$b_code = $d_name = $year = $start_date = $end_date = $msg = $error = $selected_d_id = "";

// Fetch department data
$departments = [];
$dept_query = "SELECT id, d_name FROM department";
$dept_res = mysqli_query($conn, $dept_query);
while ($dept_row = mysqli_fetch_assoc($dept_res)) {
    $departments[] = $dept_row;
}

// Handle form submission
if (isset($_POST['submit'])) {
    $b_code = $_POST['b_code'];
    $selected_d_id = $_POST['d_name'];  // The selected department ID
    $year = $_POST['year'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch department name for selected department id
    $dept_name_query = "SELECT d_name FROM department WHERE id = $selected_d_id";
    $dept_name_res = mysqli_query($conn, $dept_name_query);
    $dept_name_row = mysqli_fetch_assoc($dept_name_res);
    $d_name = $dept_name_row['d_name'];  // Get department name for display

    if ($batchid > 0) {
        // Update operation
        $sql = "UPDATE batch SET b_code = '$b_code', d_name = '$d_name', year = '$year', start_date = '$start_date', end_date = '$end_date' WHERE id = $batchid";
        $result = mysqli_query($conn, $sql);
        $msg = $result ? "Batch updated successfully" : "Something went wrong. Please try again.";
    } else {
        // Insert operation
        $sql = "INSERT INTO batch (b_code, d_name, year, start_date, end_date) VALUES ('$b_code', '$d_name', '$year', '$start_date', '$end_date')";
        $result = mysqli_query($conn, $sql);
        $msg = $result ? "Batch created successfully" : "Something went wrong. Please try again.";
    }
}

// Fetch existing batch data for update
if ($batchid > 0) {
    $query = "SELECT * FROM batch WHERE id = $batchid";
    $res = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $b_code = $row['b_code'];
        $selected_d_id = $row['d_name'];  // For displaying selected department
        $year = $row['year'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin Create/Update Batch</title>
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
                                        <?php } elseif ($error) { ?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>

                                        <div class="panel-body">
                                            <form method="POST">
                                                <div class="form-group has-success">
                                                    <label for="b_code" class="control-label">Batch Code</label>
                                                    <input type="text" name="b_code" value="<?php echo htmlentities($b_code); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="d_name" class="control-label">Department Name</label>
                                                    <select name="d_name" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php foreach ($departments as $department) { ?>
                                                            <option value="<?php echo $department['id']; ?>" <?php echo ($selected_d_id == $department['id']) ? 'selected' : ''; ?>>
                                                                <?php echo $department['d_name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="year" class="control-label">Year</label>
                                                    <input type="text" name="year" value="<?php echo htmlentities($year); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="start_date" class="control-label">Start Date</label>
                                                    <input type="date" name="start_date" value="<?php echo htmlentities($start_date); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="end_date" class="control-label">End Date</label>
                                                    <input type="date" name="end_date" value="<?php echo htmlentities($end_date); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-success"><?php echo $batchid > 0 ? "Update" : "Submit"; ?></button>
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
