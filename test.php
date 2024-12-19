<!DOCTYPE html>
<html lang="en">

<head>
    <title>Populate Dropdown using Ajax</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
</head>

<body>
    <div class="container">
        <?php
        include "includes/config.php";

        $department = $course = "";
        if (isset($_POST['submit'])) {
            $department = $_POST['department'];
            $course = $_POST['course'];
            $sql = "SELECT d.d_name, c.c_name 
                FROM department d, course c 
                WHERE d.d_code = c.d_code 
                AND d.d_code = '$department' 
                AND c.id = '$course'";
            $rs = mysqli_query($conn, $sql);
            if (mysqli_num_rows($rs) > 0) {
                $row = mysqli_fetch_array($rs);
                echo "<h3>You selected<br>Department: <b>" . $row['d_name'] . "</b>, Course: <b>" . $row['c_name'];
                echo "</b></h3>";
                $department = $course = ""; // clear selected values
            } else {
                echo "Department and Course not found in database";
            }
        }
        ?>
        <h4>Select Course</h4>
        <form id="frm" action="index.php" method="post">
            <div class="form-group col-md-12">
                <?php
                $sql = "SELECT * FROM department ORDER BY d_name";
                $rs = mysqli_query($conn, $sql);
                ?>
                <label>Department</label>
                <select name="department" id="department" class="form-control" onchange="getCourses(this.value)" required>
                    <option value="">Select Department</option>
                    <?php foreach ($rs as $arr) { ?>
                        <option value="<?php echo $arr['d_code']; ?>" <?php if ($department == $arr['d_code']) { ?> selected <?php } ?>>
                            <?php echo $arr['d_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for="course">Course:</label>
                <select name="course" id="course" class="form-control" required>
                    <option value="">Select Course</option>
                </select>
            </div>
            <br>
            <div class="col-md-12 text-center">
                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>

    <script>
        function getCourses(departmentCode) {
            if (departmentCode) {
                $.ajax({
                    url: "get_courses.php",
                    type: "POST",
                    data: {
                        d_code: departmentCode
                    },
                    success: function(data) {
                        $("#course").html(data);
                    }
                });
            } else {
                $("#course").html('<option value="">Select Course</option>');
            }
        }
    </script>
</body>

</html>