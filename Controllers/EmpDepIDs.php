<?php
include '../Database/db1.php';
$deptQuery = $conn->query("SELECT * FROM department");
 while ($dept = $deptQuery->fetch_assoc()) {
  echo "<option value='{$dept['Department_id']}'>{$dept['Name']}</option>";
 }
?>