<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect if session is not active
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
}

// Handle GET and POST data
$studentid = isset($_GET['studentid']) ? intval($_GET['studentid']) : 0;
$msg = $error = "";

// Handle form submission
if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $index_no = $_POST['index_no'];
    $email = $_POST['email'];
    $nic = $_POST['nic'];
    $course = $_POST['course'];

    if ($studentid > 0) {
        // Update existing student
        $sql = "UPDATE student SET fullname = '$fullname', index_no = '$index_no', email = '$email', nic = '$nic', course = '$course' WHERE id = $studentid";
        $result = mysqli_query($conn, $sql);
        $msg = $result ? "Student updated successfully" : "Something went wrong. Please try again.";
    } else {
        // Insert new student
        $sql = "INSERT INTO student (fullname, index_no, email, nic, course) VALUES ('$fullname', '$index_no', '$email', '$nic', '$course')";
        $result = mysqli_query($conn, $sql);
        $msg = $result ? "Student created successfully" : "Something went wrong. Please try again.";
    }
}

// Fetch data for update
$fullname = $index_no = $email = $nic = $course = "";
if ($studentid > 0) {
    $query = "SELECT * FROM student WHERE id = $studentid";
    $res = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $fullname = $row['fullname'];
        $index_no = $row['index_no'];
        $email = $row['email'];
        $nic = $row['nic'];
        $course = $row['course'];
    }
}

// Fetch course list
$courseList = [];
$query = "SELECT id, c_name FROM course";
$res = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($res)) {
    $courseList[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin Create/Update Student</title>
    <?php include_once 'script.php'; ?>
    <style>
        .errorWrap { padding: 10px; margin: 0 0 20px 0; background: #fff; border-left: 4px solid #dd3d36; box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1); }
        .succWrap { padding: 10px; margin: 0 0 20px 0; background: #fff; border-left: 4px solid #5cb85c; box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1); }
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
                                                    <input type="text" name="fullname" value="<?php echo htmlentities($fullname); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="index_no" class="control-label">Index No</label>
                                                    <input type="text" name="index_no" value="<?php echo htmlentities($index_no); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="email" class="control-label">Email</label>
                                                    <input type="email" name="email" value="<?php echo htmlentities($email); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="nic" class="control-label">NIC</label>
                                                    <input type="text" name="nic" value="<?php echo htmlentities($nic); ?>" class="form-control" required>
                                                </div>
                                                <div class="form-group has-success">
                                                    <label for="course" class="control-label">Course</label>
                                                    <select name="course" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                        <?php foreach ($courseList as $c) { ?>
                                                            <option value="<?php echo $c['id']; ?>" <?php echo $course == $c['id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlentities($c['c_name']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group has-success">
                                                    <button type="submit" name="submit" class="btn btn-success btn-labeled"><?php echo $studentid > 0 ? "Update" : "Submit"; ?><span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
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
</body>

</html>
<?php ?>
