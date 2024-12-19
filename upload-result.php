<?php include_once 'script.php' ?>
<?php include_once 'includes/config.php' ?>
<div class="card text-center">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="upload-result.php">upload result</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add-result.php">student results</a>
      </li>


    </ul>
  </div>
  <div class="card-body">
    <h5 class="card-title">Special title treatment</h5>
    <div class="container" align='center'>
      <form method="post" action="test2.php" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <br>
        <input type="submit" value="submit" name="submit">
        <?php


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $csvType = array('application/vnd.ms-excel', 'text/plain', 'text/csv');
          if (in_array($_FILES["file"]["type"], $csvType)) {
            if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
              $file = fopen($_FILES["file"]["tmp_name"], "r");
              fgetcsv($file); // Skip header row
              $sql = "INSERT INTO results (`exam_id`, `s_id`, `marks`,grade ) VALUES ";
              $rows = [];
              while (($row = fgetcsv($file)) !== FALSE) {
                $rows[] = "('" . $row[0] . "', '" . $row[1] . "')";
              }
              $sql .= implode(", ", $rows);
              if ($conn->query($sql)) {
                echo "Upload successful";
              } else {
                echo "Try again";
              }
              fclose($file);
            } else {
              echo "Please upload CSV files";
            }
          } else {
            echo "Invalid file type";
          }
        }
        ?>
      </form>
      <br>
      <a href="#" class="btn btn-primary">Go home</a>
    </div>
  </div>