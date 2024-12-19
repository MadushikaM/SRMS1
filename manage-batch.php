<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    // Code for Deletion
    if (isset($_GET['id'])) {
        $batchid = $_GET['id'];
        $sql = "DELETE FROM batch WHERE id = $batchid";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Batch deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error deleting batch.");</script>';
        }

        echo "<script>window.location.href ='manage-batch.php'</script>";
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Manage Batches</title>
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
                                    <h2 class="title">Manage Batches</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Batches</li>
                                        <li class="active">Manage Batches</li>
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
                                                    <h5>View Batch Info</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Batch Code</th>
                                                            <th>Course Code</th>
                                                            <th>Year</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Batch Code</th>
                                                            <th>Course Code</th>
                                                            <th>Year</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT id, b_code, c_code, year, start_date, end_date FROM batch";
                                                        $result = mysqli_query($conn, $sql);
                                                        $cnt = 1;
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($row['b_code']); ?></td>
                                                                    <td><?php echo htmlentities($row['c_code']); ?></td>
                                                                    <td><?php echo htmlentities($row['year']); ?></td>
                                                                    <td><?php echo htmlentities($row['start_date']); ?></td>
                                                                    <td><?php echo htmlentities($row['end_date']); ?></td>
                                                                    <td>
                                                                        <a href="edit-batch.php?batchid=<?php echo htmlentities($row['id']); ?>" class="btn btn-info btn-xs">Edit</a>
                                                                        <a href="?id=<?php echo $row['id']; ?>" onClick="return confirm('Are you sure you want to delete this batch?')" class="btn btn-danger btn-xs">Delete</a>
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
