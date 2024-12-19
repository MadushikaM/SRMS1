<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    // Code for Deletion
    if (isset($_GET['id'])) {
        $studentid = $_GET['id'];
        $sql = "DELETE FROM student WHERE id = $studentid";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Student deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error deleting student.");</script>';
        }

        echo "<script>window.location.href ='manage-students.php'</script>";
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Manage Students</title>
        <?php include_once 'script.php' ?>
    </head>

    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <!-- TOP NAVBAR -->
            <?php include('includes/topbar.php'); ?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/leftbar.php'); ?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Manage Students</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Students</li>
                                        <li class="active">Manage Students</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>View Student Info</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body p-20">
                                                <!-- Course Filter -->
                                                <form method="GET" action="">
                                                    <label for="courseFilter">Filter by Course:</label>
                                                    <select name="courseFilter" id="courseFilter" class="form-control" style="width: 300px; display: inline-block;">
                                                        <option value="">All Courses</option>
                                                        <?php
                                                        $query = mysqli_query($conn, "SELECT id, c_name FROM course");
                                                        while ($row = mysqli_fetch_assoc($query)) {
                                                            $selected = isset($_GET['courseFilter']) && $_GET['courseFilter'] == $row['id'] ? 'selected' : '';
                                                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlentities($row['c_name']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                </form>

                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Full Name</th>
                                                            <th>Index No</th>
                                                            <th>Email</th>
                                                            <th>NIC</th>
                                                            <th>Course</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Full Name</th>
                                                            <th>Index No</th>
                                                            <th>Email</th>
                                                            <th>NIC</th>
                                                            <th>Course</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        $filter = "";
                                                        if (isset($_GET['courseFilter']) && $_GET['courseFilter'] != "") {
                                                            $courseId = intval($_GET['courseFilter']);
                                                            $filter = " WHERE course = $courseId";
                                                        }

                                                        $sql = "SELECT s.id, s.fullname, s.index_no, s.email, s.nic, c.c_name AS course_name 
                                                                FROM student s 
                                                                LEFT JOIN course c ON s.course = c.id" . $filter;
                                                        $result = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($row['id']); ?></td>
                                                                    <td><?php echo htmlentities($row['fullname']); ?></td>
                                                                    <td><?php echo htmlentities($row['index_no']); ?></td>
                                                                    <td><?php echo htmlentities($row['email']); ?></td>
                                                                    <td><?php echo htmlentities($row['nic']); ?></td>
                                                                    <td><?php echo htmlentities($row['course_name']); ?></td>
                                                                    <td>
                                                                        <a href="edit-student.php?studentid=<?php echo htmlentities($row['id']); ?>" class="btn btn-info btn-xs">Edit</a>
                                                                        <a href="?id=<?php echo $row['id']; ?>" onClick="return confirm('Are you sure you want to delete this student?')" class="btn btn-danger btn-xs">Delete</a>
                                                                    </td>
                                                                </tr>
                                                        <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="7" class="text-center">No Records Found</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
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
        <script>
            $(function($) {
                $('#example').DataTable();
            });
        </script>
    </body>

    </html>
<?php } ?>
