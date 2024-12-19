<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    // Code for Deletion
    if (isset($_GET['id'])) {
        $moduleid = $_GET['id'];
        $sql = "DELETE FROM module WHERE id = $moduleid";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Module deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error deleting module.");</script>';
        }

        echo "<script>window.location.href ='manage-module.php'</script>";
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Manage Modules</title>
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
                                    <h2 class="title">Manage Modules</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Modules</li>
                                        <li class="active">Manage Modules</li>
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
                                                    <h5>View Module Info</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Module Code</th>
                                                            <th>Module Name</th>
                                                            <th>Semester</th>
                                                            <th>Course Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Module Code</th>
                                                            <th>Module Name</th>
                                                            <th>Semester</th>
                                                            <th>Course Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT m.id, m.m_code, m.m_name, m.semester,  c.c_name 
                                                                FROM module m JOIN course c ON m.c_name = c.id";
                                                        $result = mysqli_query($conn, $sql);
                                                        $cnt = 1;
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($row['m_code']); ?></td>
                                                                    <td><?php echo htmlentities($row['m_name']); ?></td>
                                                                    <td><?php echo htmlentities($row['semester']); ?></td>
                                                                    <td><?php echo htmlentities($row['c_name']); ?></td>
                                                                    
                                                                    <td>
                                                                        <a href="edit-module.php?moduleid=<?php echo htmlentities($row['id']); ?>" class="btn btn-info btn-xs">Edit</a>
                                                                        <a href="?id=<?php echo $row['id']; ?>" onClick="return confirm('Are you sure you want to delete this module?')" class="btn btn-danger btn-xs">Delete</a>
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