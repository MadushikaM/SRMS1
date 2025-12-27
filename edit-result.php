<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    $resultid = isset($_GET['resultid']) ? intval($_GET['resultid']) : 0;

    if (isset($_POST['submit'])) {
        $exam_id = $_POST['exam_id'];
        $nic = $_POST['nic'];
        $semester = $_POST['semester'];
        $marks = $_POST['marks'];
        $grade = $_POST['grade'];

        if ($resultid > 0) {
            // Update operation
            $sql = "UPDATE results SET exam_id = '$exam_id', nic = '$nic', semester = '$semester', marks = '$marks', grade = '$grade' WHERE id = $resultid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Result updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO results (exam_id, nic, semester, marks, grade) VALUES ('$exam_id', '$nic', '$semester', '$marks', '$grade')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Result created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    // Fetch existing result data for update
    $exam_id = "";
    $nic = "";
    $semester = "";
    $marks = "";
    $grade = "";
    if ($resultid > 0) {
        $query = "SELECT * FROM results WHERE id = $resultid";
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);
        $exam_id = $row['exam_id'];
        $nic = $row['nic'];
        $semester = $row['semester'];
        $marks = $row['marks'];
        $grade = $row['grade'];
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SMS Admin Create/Update Result</title>
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
                                    <h2 class="title"><?php echo $resultid > 0 ? "Update Result" : "Create Result"; ?></h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="#">Results</a></li>
                                        <li class="active"><?php echo $resultid > 0 ? "Update Result" : "Create Result"; ?></li>
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
                                                    <h5><?php echo $resultid > 0 ? "Update Result" : "Create Result"; ?></h5>
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
                                                        <label for="exam_id">Exam ID</label>
                                                        <select name="exam_id" class="form-control" required>
                                                            <option value="">Select Exam</option>
                                                            <?php
                                                            $query = "SELECT exam_id FROM exam";
                                                            $result = mysqli_query($conn, $query);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $selected = ($row['exam_id'] == $exam_id) ? "selected" : "";
                                                                echo "<option value='" . $row['exam_id'] . "' $selected>" . $row['exam_id'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <label for="nic" class="control-label">NIC</label>
                                                        <input type="text" name="nic" value="<?php echo htmlentities($nic); ?>" required="required" class="form-control">
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <label for="semester">Semester</label>
                                                        <select name="semester" class="form-control" required>
                                                            <option value="">Select Semester</option>
                                                            <option value="Semester 1" <?php echo ($semester == "Semester 1") ? "selected" : ""; ?>>Semester 1</option>
                                                            <option value="Semester 2" <?php echo ($semester == "Semester 2") ? "selected" : ""; ?>>Semester 2</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <label for="marks" class="control-label">Marks</label>
                                                        <input type="text" name="marks" value="<?php echo htmlentities($marks); ?>" required="required" class="form-control">
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <label for="grade">Grade</label>
                                                        <select name="grade" class="form-control" required>
                                                            <option value="">Select Grade</option>
                                                            <option value="A" <?php echo ($grade == "A") ? "selected" : ""; ?>>A</option>
                                                            <option value="B" <?php echo ($grade == "B") ? "selected" : ""; ?>>B</option>
                                                            <option value="C" <?php echo ($grade == "C") ? "selected" : ""; ?>>C</option>
                                                            <option value="D" <?php echo ($grade == "D") ? "selected" : ""; ?>>D</option>
                                                            <option value="F" <?php echo ($grade == "F") ? "selected" : ""; ?>>F</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group has-success">
                                                        <button type="submit" name="submit" class="btn btn-success btn-labeled">
                                                            <?php echo $resultid > 0 ? "Update" : "Submit"; ?>
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
