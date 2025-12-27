<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect to login if session not set
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $batches = $_POST['batch'];
    $modules = $_POST['module'];
    $dates = $_POST['date'];
    $times = $_POST['time'];

    $success = true;

    foreach ($modules as $index => $moduleId) {
        $batchId = $batches[$index];
        $date = $dates[$index];
        $time = $times[$index];

        // Insert exam details into the database
        $sql = "INSERT INTO exam (module, batch, date, time , status) VALUES ('$moduleId', '$batchId', '$date', '$time' ,'pending')";
        if (!mysqli_query($conn, $sql)) {
            $success = false;
        }
    }

    if ($success) {
        $msg = "Exams created successfully!";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Create Exam</title>
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
                                <h2 class="title">Create Exam</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">Exam</a></li>
                                    <li class="active">Create Exam</li>
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
                                            <h5>Create Exam</h5>
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
                                                <!-- Select Department -->
                                                <div class="form-group">
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
                                                <div class="form-group">
                                                    <label for="course" class="control-label">Course</label>
                                                    <select name="course" id="course" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                    </select>
                                                </div>
                                                <!-- Select Batch -->
                                                <div class="form-group">
                                                    <label for="batch" class="control-label">Batch</label>
                                                    <select name="batch[]" id="batch" class="form-control" required>
                                                        <option value="">Select Batch</option>
                                                    </select>
                                                </div>

                                                <!-- Table for Modules -->
                                                <div class="form-group">
                                                    <label>Modules</label>
                                                    <table class="table table-bordered" id="moduleTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Module</th>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
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

    <!-- AJAX Scripts -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script>
        const selectedBatches = new Set();

        // Fetch courses based on department
        document.getElementById('d_name').addEventListener('change', function() {
            const d_code = this.value;
            const courseDropdown = document.getElementById('course');
            courseDropdown.innerHTML = '<option value="">Select Course</option>';
            if (d_code) {
                fetch('get_courses.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `d_code=${encodeURIComponent(d_code)}`
                    })
                    .then(response => response.json())
                    .then(courses => {
                        courses.forEach(course => {
                            const option = document.createElement('option');
                            option.value = course.c_code;
                            option.textContent = course.c_name;
                            courseDropdown.appendChild(option);
                        });
                    });
            }
        });

        // Fetch batches based on selected course
        document.getElementById('course').addEventListener('change', function() {
            const c_code = this.value;
            const batchDropdown = document.getElementById('batch');
            batchDropdown.innerHTML = '<option value="">Select Batch</option>';
            if (c_code) {
                fetch('get_batches.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `c_code=${encodeURIComponent(c_code)}`
                    })
                    .then(response => response.json())
                    .then(batches => {
                        batches.forEach(batch => {
                            if (!selectedBatches.has(batch.b_code)) {
                                const option = document.createElement('option');
                                option.value = batch.b_code;
                                option.textContent = batch.b_code;
                                batchDropdown.appendChild(option);
                            }
                        });
                    });
            }
        });

        // Add selected batch to the set to prevent duplicate selection
        document.getElementById('batch').addEventListener('change', function() {
            selectedBatches.add(this.value);
        });

        // Fetch modules based on selected course
        document.getElementById('course').addEventListener('change', function() {
            var c_code = this.value;
            var moduleTableBody = document.querySelector('#moduleTable tbody');
            moduleTableBody.innerHTML = '';
            if (c_code) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'get_modules.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var modules = JSON.parse(xhr.responseText);
                        modules.forEach(function(module) {
                            var row = document.createElement('tr');

                            var moduleCell = document.createElement('td');
                            moduleCell.textContent = module.m_name;
                            row.appendChild(moduleCell);

                            var dateCell = document.createElement('td');
                            var dateInput = document.createElement('input');
                            dateInput.type = 'date';
                            dateInput.name = 'date[]';
                            dateInput.required = true;
                            dateCell.appendChild(dateInput);
                            row.appendChild(dateCell);

                            var timeCell = document.createElement('td');
                            var timeInput = document.createElement('input');
                            timeInput.type = 'time';
                            timeInput.name = 'time[]';
                            timeInput.required = true;
                            timeCell.appendChild(timeInput);
                            row.appendChild(timeCell);

                            var hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'module[]';
                            hiddenInput.value = module.id;
                            row.appendChild(hiddenInput);

                            moduleTableBody.appendChild(row);
                        });
                    }
                };
                xhr.send('c_code=' + encodeURIComponent(c_code));
            }
        });
    </script>
    
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