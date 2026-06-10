<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Database/db1.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $designationID = mysqli_real_escape_string($conn, $_POST['designationID']);

    //Check if emp exist in this designation
    $checkQuery = "SELECT COUNT(*) AS count FROM employee WHERE Designation_ID = '$designationID'";
    $result = mysqli_query($conn, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo json_encode(["status" => "error", "message" => "Cannot delete. Employees exist in this Designation."]);
        exit();
    }
    $query = "DELETE FROM designation WHERE Designation_ID = '$designationID'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        exit();
    }
}
?>