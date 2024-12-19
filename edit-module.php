<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $moduleid = isset($_GET['moduleid']) ? intval($_GET['moduleid']) : 0;

    if (isset($_POST['submit'])) {
        $mcode = $_POST['mcode'];  // Module code
        $mname = $_POST['mname'];  // Module name
        $semester = $_POST['semester'];  // Semester
        $dcode = $_POST['dcode'];  // Department code
        $cname = $_POST['cname'];  // Course name

        if ($moduleid > 0) {
            // Update operation
            $sql = "UPDATE module SET m_code = '$mcode', m_name = '$mname', semester = '$semester', d_code = '$dcode', c_name = '$cname' WHERE id = $moduleid";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Module updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO module (m_code, m_name, semester, d_code, c_name) VALUES ('$mcode', '$mname', '$semester', '$dcode', '$cname')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $msg = "Module created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    // Fetch existing module data for update
    $mcode = "";
    $mname = "";
    $semester = "";
    $dcode = "";
    $cname = "";
    if ($moduleid > 0) {
        $query = "SELECT * FROM module WHERE id = $moduleid";
        $res = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($res);
        $mcode = $row['m_code'];
        $mname = $row['m_name'];
        $semester = $row['semester'];
        $dcode = $row['d_code'];
        $cname = $row['c_name'];
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin Create/Update Module</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // When a department is selected, load courses related to that department
            $("#dcode").change(function() {
                var d_code = $(this).val(); // Get selected department code
                if (d_code != "") {
                    $.ajax({
                        url: "get_courses.php", // Fetch courses based on the department code
                        method: "POST",
                        data: {
                            d_code: d_code
                        },
                        success: function(data) {
                            $("#cname").html(data); // Populate the course dropdown
                        }
                    });
                } else {
                    $("#cname").html("<option value=''>Select Course</option>");
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
                                <h2 class="title"><?php echo $moduleid > 0 ? "Update Module" : "Create Module"; ?></h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">Module</a></li>
                                    <li class="active"><?php echo $moduleid > 0 ? "Update Module" : "Create Module"; ?></li>
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
                                                <h5><?php echo $moduleid > 0 ? "Update Module" : "Create Module"; ?></h5>
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
                                                <!-- Module Code -->
                                                <div class="form-group">
                                                    <label for="mcode">Module Code</label>
                                                    <input type="text" name="mcode" value="<?php echo htmlentities($mcode); ?>" class="form-control" required id="mcode">
                                                </div>

                                                <!-- Module Name -->
                                                <div class="form-group">
                                                    <label for="mname">Module Name</label>
                                                    <input type="text" name="mname" value="<?php echo htmlentities($mname); ?>" class="form-control" required id="mname">
                                                </div>

                                                <!-- Semester -->
                                                <div class="form-group">
                                                    <label for="semester">Semester</label>
                                                    <select name="semester" id="semester" class="form-control" required>
                                                        <option value="">Select Semester</option>
                                                        <option value="Semester 1" <?php echo $semester == 'Semester 1' ? 'selected' : ''; ?>>Semester 1</option>
                                                        <option value="Semester 2" <?php echo $semester == 'Semester 2' ? 'selected' : ''; ?>>Semester 2</option>
                                                        <option value="Semester 3" <?php echo $semester == 'Semester 3' ? 'selected' : ''; ?>>Semester 3</option>
                                                        <option value="Semester 4" <?php echo $semester == 'Semester 4' ? 'selected' : ''; ?>>Semester 4</option>
                                                    </select>
                                                </div>

                                                <!-- Department Dropdown -->
                                                <div class="form-group">
                                                    <label for="dcode">Department</label>
                                                    <select name="dcode" id="dcode" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php
                                                        $sql = "SELECT * FROM department ORDER BY d_name";
                                                        $result = mysqli_query($conn, $sql);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='" . $row['d_code'] . "' " . ($dcode == $row['d_code'] ? 'selected' : '') . ">" . $row['d_name'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Course Dropdown -->
                                                <div class="form-group">
                                                    <label for="cname">Course</label>
                                                    <select name="cname" id="cname" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                        <!-- Course options will be dynamically loaded based on the selected department -->
                                                    </select>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-success"><?php echo $moduleid > 0 ? "Update" : "Submit"; ?></button>
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
