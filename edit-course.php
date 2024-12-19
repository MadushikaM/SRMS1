<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    $courseid = isset($_GET['courseid']) ? intval($_GET['courseid']) : 0;

    if (isset($_POST['submit'])) {
        $ccode = $_POST['ccode'];
        $cname = $_POST['cname'];
        $dcode = $_POST['dcode'];

        if ($courseid > 0) {
            // Update operation
            $sql = "UPDATE course SET c_code = '$ccode', c_name = '$cname', d_code = '$dcode' WHERE id = $courseid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Course updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO course (c_code, c_name, d_code) VALUES ('$ccode', '$cname', '$dcode')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Course created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    // Fetch existing course data for update
    $ccode = "";
    $cname = "";
    $dcode = "";
    if ($courseid > 0) {
        $query = "SELECT * FROM course WHERE id = $courseid";
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);
        $ccode = $row['c_code'];
        $cname = $row['c_name'];
        $dcode = $row['d_code'];
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin Create/Update Course</title>
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
                                <h2 class="title"><?php echo $courseid > 0 ? "Update Course" : "Create Course"; ?></h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">Course</a></li>
                                    <li class="active"><?php echo $courseid > 0 ? "Update Course" : "Create Course"; ?></li>
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
                                                <h5><?php echo $courseid > 0 ? "Update Course" : "Create Course"; ?></h5>
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
                                                <div class="form-group has-success">
                                                    <label for="success" class="control-label">Course Code</label>
                                                    <div class="">
                                                        <input type="text" name="ccode" value="<?php echo htmlentities($ccode); ?>" class="form-control" required="required" id="success">
                                                    </div>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="success" class="control-label">Course Name</label>
                                                    <div class="">
                                                        <input type="text" name="cname" value="<?php echo htmlentities($cname); ?>" required="required" class="form-control" id="success">
                                                    </div>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="success" class="control-label">Department Code</label>
                                                    <div class="">
                                                        <input type="text" name="dcode" value="<?php echo htmlentities($dcode); ?>" required="required" class="form-control" id="success">
                                                    </div>
                                                </div>
                                                <div class="form-group has-success">
                                                    <div class="">
                                                        <button type="submit" name="submit" class="btn btn-success btn-labeled"><?php echo $courseid > 0 ? "Update" : "Submit"; ?><span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                                                    </div>
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
