<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    // Code for Deletion
    if (isset($_GET['id'])) {
        $resultid = $_GET['id']; // Deleting result instead of course
        $sql = "DELETE FROM tblresult WHERE id = $resultid";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Result deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error deleting result.");</script>';
        }

        echo "<script>window.location.href ='manage-results.php'</script>";
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Manage Results</title>
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
                                    <h2 class="title">Manage Results</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Results</li>
                                        <li class="active">Manage Results</li>
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
                                                    <h5>View Exam Results</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Exam ID</th>
                                                            <th>Student ID</th>
                                                            <th>Subject</th>
                                                            <th>Marks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Exam ID</th>
                                                            <th>Student ID</th>
                                                            <th>Subject</th>
                                                            <th>Marks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        // SQL to fetch exam results with exam_id, student_id, and marks
                                                        $sql = "SELECT tr.exam_id, tr.s_id, tr.marks, sub.SubjectName 
                                                                FROM tblresult tr
                                                                JOIN tblsubjects sub ON sub.id = tr.SubjectId";
                                                        $result = mysqli_query($conn, $sql);
                                                        $cnt = 1;
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($row['exam_id']); ?></td>
                                                                    <td><?php echo htmlentities($row['s_id']); ?></td>
                                                                    <td><?php echo htmlentities($row['SubjectName']); ?></td>
                                                                    <td><?php echo htmlentities($row['marks']); ?></td>
                                                                    <td>
                                                                        <a href="edit-result.php?resultid=<?php echo htmlentities($row['exam_id']); ?>" class="btn btn-info btn-xs">Edit</a>
                                                                        <a href="?id=<?php echo $row['exam_id']; ?>" onClick="return confirm('Are you sure you want to delete this result?')" class="btn btn-danger btn-xs">Delete</a>
                                                                    </td>
                                                                </tr>
                                                        <?php $cnt++;
                                                            }
                                                        } ?>
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
