<?php
include '../Database/db1.php';
$desigQuery = $conn->query("SELECT * FROM designation");
while ($desig = $desigQuery->fetch_assoc()) {
    echo "<option value='{$desig['Designation_id']}'>{$desig['Name']}</option>";
}
?>