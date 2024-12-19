<div class="login-bg-color" >
    <div class="row">
    <div class="col-md-6 ">
        <div class="col-md-6 l-md-offset-4">
            <div class="panel login-box">
                <div class="panel-heading">
                    
                </div>
                <div class="panel-body p-20" style="background-color: #aab7b7;">



                    <form action="result.php" method="post">
                        <div class="form-group">
                            <label for="rollid">Enter your NIC number</label>
                            <input type="text" class="form-control" id="rollid" placeholder="Enter Your Roll Id" autocomplete="off" name="rollid">
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2 control-label">Class</label>
                            <select name="class" class="form-control" id="default" required="required">
                                <option value="">Select Class</option>
                                <?php $sql = "SELECT * from tblclasses";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {   ?>
                                        <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassName); ?>&nbsp; Section-<?php echo htmlentities($result->Section); ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>


                        <div class="form-group mt-20">
                            <div style="color: white;">

                                <button type="submit" class="btn btn- btn-labeled pull-right" style="background-color: #1a2d42;">Search<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <a href="index.php" style="color: white;">Back to Home</a>
                        </div>
                    </form>

                    <hr>

                </div>
            </div>
            <!-- /.panel -->
            <p class="text-muted text-center"><small>Student Result Management System</small></p>
        </div>
    </div>
        <!-- /.col-md-6 col-md-offset-3 -->
    </div>
    <!-- /.row -->
</div>
    </div>
<!-- /. -->

</div>