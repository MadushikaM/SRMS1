<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $examid = isset($_GET['examid']) ? intval($_GET['examid']) : 0;

    if (isset($_POST['submit'])) {
        $module = $_POST['module'];  // Module name
        $batch = $_POST['batch'];    // Batch code (from dropdown)
        $date = $_POST['date'];      // Exam date
        $time = $_POST['time'];      // Exam time

        if ($examid > 0) {
            // Update operation
            $sql = "UPDATE exam SET module = '$module', batch = '$batch', date = '$date', time = '$time' WHERE exam_id = $examid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Exam updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO exam (module, batch, date, time) VALUES ('$module', '$batch', '$date', '$time')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Exam created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    // Fetch existing exam data for update
    $module = "";
    $batch = "";
    $date = "";
    $time = "";
    if ($examid > 0) {
        $query = "SELECT * FROM exam WHERE exam_id = $examid";
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);
        $module = $row['module'];
        $batch = $row['batch'];
        $date = $row['date'];
        $time = $row['time'];
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin Create/Update Exam</title>
    <?php include_once 'script.php' ?>
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
                                <h2 class="title"><?php echo $examid > 0 ? "Update Exam" : "Create Exam"; ?></h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">Exam</a></li>
                                    <li class="active"><?php echo $examid > 0 ? "Update Exam" : "Create Exam"; ?></li>
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
                                                <h5><?php echo $examid > 0 ? "Update Exam" : "Create Exam"; ?></h5>
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
                                                <!-- Module -->
                                                <div class="form-group">
                                                    <label for="module">Module</label>
                                                    <input type="text" name="module" value="<?php echo htmlentities($module); ?>" class="form-control" required id="module">
                                                </div>

                                                <!-- Batch Dropdown -->
                                                <div class="form-group">
                                                    <label for="batch">Batch</label>
                                                    <select name="batch" id="batch" class="form-control" required>
                                                        <option value="">Select Batch</option>
                                                        <?php
                                                        // Fetch batches from the database
                                                        $sql = "SELECT * FROM batch ORDER BY b_code";
                                                        $result = mysqli_query($conn, $sql);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='" . $row['b_code'] . "' " . ($batch == $row['b_code'] ? 'selected' : '') . ">" . $row['b_code'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Exam Date -->
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <input type="date" name="date" value="<?php echo htmlentities($date); ?>" class="form-control" required id="date">
                                                </div>

                                                <!-- Exam Time -->
                                                <div class="form-group">
                                                    <label for="time">Time</label>
                                                    <input type="time" name="time" value="<?php echo htmlentities($time); ?>" class="form-control" required id="time">
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-success"><?php echo $examid > 0 ? "Update" : "Submit"; ?></button>
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
