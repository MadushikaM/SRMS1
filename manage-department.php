<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    // Code for Deletion
    if (isset($_GET['id'])) {
        $deptid = $_GET['id'];
        $sql = "DELETE FROM department WHERE id = $deptid";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Department deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error deleting department.");</script>';
        }

        echo "<script>window.location.href ='manage-department.php'</script>";
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Manage Departments</title>
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
                                    <h2 class="title">Manage Departments</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Departments</li>
                                        <li class="active">Manage Departments</li>
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
                                                    <h5>View Departments Info</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Department Code</th>
                                                            <th>Department Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Department Code</th>
                                                            <th>Department Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM department";
                                                        $result = mysqli_query($conn, $sql);
                                                        $cnt = 1;
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($row['d_code']); ?></td>
                                                                    <td><?php echo htmlentities($row['d_name']); ?></td>
                                                                    <td>
                                                                        <a href="edit-department.php?deptid=<?php echo htmlentities($row['id']); ?>" class="btn btn-info btn-xs">Edit</a>
                                                                        <a href="?id=<?php echo $row['id']; ?>" onClick="return confirm('Are you sure you want to delete this department?')" class="btn btn-danger btn-xs">Delete</a>
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

        <!-- COMMON JS FILES -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/DataTables/datatables.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(function($) {
                $('#example').DataTable();
            });
        </script>
    </body>

    </html>
<?php } ?>