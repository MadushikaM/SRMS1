<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $studentid = isset($_GET['studentid']) ? intval($_GET['studentid']) : 0; // Get student ID for update operation

    if (isset($_POST['submit'])) {
        $fullname = $_POST['fullname'];
        $index_no = $_POST['index_no'];
        $email = $_POST['email'];
        $nic = $_POST['nic'];
        $course = $_POST['course'];

        if ($studentid > 0) {
            // Update operation
            $sql = "UPDATE student SET fullname = '$fullname', index_no = '$index_no', email = '$email', nic = '$nic', course = '$course' WHERE id = $studentid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Student updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO student (fullname, index_no, email, nic, course) VALUES ('$fullname', '$index_no', '$email', '$nic', '$course')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Student created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    // Fetch student data for update operation
    $fullname = "";
    $index_no = "";
    $email = "";
    $nic = "";
    $course = "";
    if ($studentid > 0) {
        $sql = "SELECT * FROM student WHERE id = $studentid";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $fullname = $row['fullname'];
            $index_no = $row['index_no'];
            $email = $row['email'];
            $nic = $row['nic'];
            $course = $row['course'];
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SMS Admin <?php echo $studentid > 0 ? "Update Student" : "Create Student"; ?></title>
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
                                    <h2 class="title"><?php echo $studentid > 0 ? "Update Student" : "Create Student"; ?></h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="#">Student</a></li>
                                        <li class="active"><?php echo $studentid > 0 ? "Update Student" : "Create Student"; ?></li>
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
                                                    <h5><?php echo $studentid > 0 ? "Update Student" : "Create Student"; ?></h5>
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
                                                        <label for="fullname" class="control-label">Full Name</label>
                                                        <div class="">
                                                            <input type="text" name="fullname" value="<?php echo htmlentities($fullname); ?>" class="form-control" required="required" id="fullname">
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <label for="index_no" class="control-label">Index Number</label>
                                                        <div class="">
                                                            <input type="text" name="index_no" value="<?php echo htmlentities($index_no); ?>" required="required" class="form-control" id="index_no">
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <label for="email" class="control-label">Email</label>
                                                        <div class="">
                                                            <input type="email" name="email" value="<?php echo htmlentities($email); ?>" required="required" class="form-control" id="email">
                                                            <?php
                                                             // email validation
                                                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                                $error = "Invalid email format.";
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <label for="nic" class="control-label">NIC</label>
                                                        <div class="">
                                                            <input type="text" name="nic" value="<?php echo htmlentities($nic); ?>" required="required" class="form-control" id="nic">

                                                            <?php
                                                            //nic validation
                                                            if (!preg_match("/^[0-13]{13}[vVxX]$/", $nic)) {
                                                                $error = "Invalid NIC format. Use 9 digits followed by 'V/v' or 'X/x'.";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <label for="course" class="control-label">Course</label>
                                                        <div class="">
                                                            <select name="course" class="form-control" required="required" id="course">
                                                                <option value="">Select Course</option>
                                                                <?php
                                                                $query = mysqli_query($conn, "SELECT id, c_name FROM course");
                                                                while ($row = mysqli_fetch_array($query)) {
                                                                    $selected = $row['id'] == $course ? "selected" : "";
                                                                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['c_name'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <div class="">
                                                            <button type="submit" name="submit" class="btn btn-success btn-labeled"><?php echo $studentid > 0 ? "Update" : "Submit"; ?><span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
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
