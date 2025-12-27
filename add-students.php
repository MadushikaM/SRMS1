<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
} else {
    $studentid = isset($_GET['studentid']) ? intval($_GET['studentid']) : 0;

    if (isset($_POST['submit'])) {
        $fullname = $_POST['fullname'];
        $index_no = $_POST['index_no'];
        $email = $_POST['email'];
        $nic = $_POST['nic'];
        $course = $_POST['course'];
        $batch = $_POST['batch'];

        if ($studentid > 0) {
            // Update operation
            $sql = "UPDATE student SET fullname = ?, index_no = ?, email = ?, nic = ?, course = ?, b_code = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $fullname, $index_no, $email, $nic, $course, $batch, $studentid);
            $result = $stmt->execute();

            if ($result) {
                $msg = "Student updated successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        } else {
            // Insert operation
            $sql = "INSERT INTO student (fullname, index_no, email, nic, course, b_code) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $fullname, $index_no, $email, $nic, $course, $batch);
            $result = $stmt->execute();

            if ($result) {
                $msg = "Student created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    // Fetch student data for update operation
    $fullname = $index_no = $email = $nic = $course = $batch = "";
    if ($studentid > 0) {
        $sql = "SELECT * FROM student WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $studentid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fullname = $row['fullname'];
            $index_no = $row['index_no'];
            $email = $row['email'];
            $nic = $row['nic'];
            $course = $row['course'];
            $batch = $row['b_code'];
        }
    }

    // Handle CSV file upload
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
        $allowedExtensions = ['xlsx'];
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (in_array($fileExtension, $allowedExtensions)) {
            if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
                $file = fopen($_FILES["file"]["tmp_name"], "r");
                fgetcsv($file); // Skip header row

                while (($row = fgetcsv($file)) !== FALSE) {
                    $fullname = mysqli_real_escape_string($conn, $row[0]);
                    $index_no = mysqli_real_escape_string($conn, $row[1]);
                    $email = mysqli_real_escape_string($conn, $row[2]);
                    $nic = mysqli_real_escape_string($conn, $row[3]);
                    $course = mysqli_real_escape_string($conn, $row[4]);
                    $batch = mysqli_real_escape_string($conn, $row[5]);

                    $sql = "INSERT INTO student (fullname, index_no, email, nic, course, b_code) VALUES ('$fullname', '$index_no', '$email', '$nic' , '$course' , '$batch')";
                    if (!mysqli_query($conn, $sql)) {
                        $error = "Database error: " . mysqli_error($conn);
                        break;
                    }
                }
                fclose($file);

                if (!$error) {
                    $msg = "File uploaded successfully!";
                }
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Invalid file type. Please upload a valid excel file.";
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
        <title>SMS Admin <?php echo $studentid > 0 ? "Update Student" : "Create Student"; ?></title>
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
                                                <!-- Excel File Upload Form -->
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="file">Upload Excel File</label>
                                                        <input type="file" name="file" id="file" class="form-control" accept=".xls,.xlsx">
                                                        <small>Example Format: <a href="student.xlsx" target="_blank">Download Sample</a></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" name="import_excel" class="btn btn-success">Import Excel</button>
                                                    </div>
                                                </form>
                                                <div class="panel-body">
                                                    <form method="POST">
                                                        <div class="form-group has-success">
                                                            <label for="fullname" class="control-label">Full Name</label>
                                                            <div class="">
                                                                <input type="text" name="fullname" value="<?php echo htmlentities($fullname); ?>" class="form-control" required id="fullname">
                                                            </div>
                                                        </div>
                                                        <div class="form-group has-success">
                                                            <label for="index_no" class="control-label">Index Number</label>
                                                            <div class="">
                                                                <input type="text" name="index_no" value="<?php echo htmlentities($index_no); ?>" required class="form-control" id="index_no">
                                                            </div>
                                                        </div>
                                                        <div class="form-group has-success">
                                                            <label for="email" class="control-label">Email</label>
                                                            <div class="">
                                                                <input type="email" name="email" value="<?php echo htmlentities($email); ?>" required class="form-control" id="email">
                                                            </div>
                                                        </div>
                                                        <div class="form-group has-success">
                                                            <label for="nic" class="control-label">NIC</label>
                                                            <div class="">
                                                                <input type="text" name="nic" value="<?php echo htmlentities($nic); ?>" required class="form-control" id="nic">
                                                            </div>
                                                        </div>

                                                        <!-- Select Department -->
                                                        <div class="form-group has-success">
                                                            <label for="d_name" class="control-label">Department</label>
                                                            <select name="d_name" id="d_name" class="form-control" required>
                                                                <option value="">Select Department</option>
                                                                <?php
                                                                $query = "SELECT * FROM department";
                                                                $result = mysqli_query($conn, $query);
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    echo "<option value='{$row['d_code']}'>{$row['d_name']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <!-- Select Course -->
                                                        <div class="form-group has-success">
                                                            <label for="course" class="control-label">Course</label>
                                                            <select name="course" id="course" class="form-control" required>
                                                                <option value="">Select Course</option>
                                                            </select>
                                                        </div>

                                                        <!-- Select Batch -->
                                                        <div class="form-group has-success">
                                                            <label for="batch" class="control-label">Batch</label>
                                                            <select name="batch" id="batch" class="form-control" required>
                                                                <option value="">Select Batch</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group has-success">
                                                            <div class="">
                                                                <button type="submit" name="submit" class="btn btn-success btn-labeled">
                                                                    <?php echo $studentid > 0 ? "Update" : "Submit"; ?>
                                                                    <span class="btn-label btn-label-right"><i class="fa fa-check"></i></span>
                                                                </button>
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
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            // Fetch courses based on department
            document.getElementById('d_name').addEventListener('change', function() {
                var d_code = this.value;
                var courseDropdown = document.getElementById('course');
                courseDropdown.innerHTML = '<option value="">Select Course</option>';
                if (d_code) {
                    fetch('get_courses.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'd_code=' + encodeURIComponent(d_code)
                        })
                        .then(response => response.json())
                        .then(courses => {
                            courses.forEach(course => {
                                var option = document.createElement('option');
                                option.value = course.c_code;
                                option.textContent = course.c_name;
                                courseDropdown.appendChild(option);
                            });
                        });
                }
            });

            // Fetch batches based on course
            document.getElementById('course').addEventListener('change', function() {
                var c_code = this.value;
                var batchDropdown = document.getElementById('batch');
                batchDropdown.innerHTML = '<option value="">Select Batch</option>';
                if (c_code) {
                    fetch('get_batches.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'c_code=' + encodeURIComponent(c_code)
                        })
                        .then(response => response.json())
                        .then(batches => {
                            batches.forEach(batch => {
                                var option = document.createElement('option');
                                option.value = batch.b_code;
                                option.textContent = batch.b_code;
                                batchDropdown.appendChild(option);
                            });
                        });
                }
            });
        </script>
    </body>

    </html>
