<?php
// fetch_employee_details.php

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$dbName = "payrollease";
$dbUsername = "root";
$dbPassword = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
// Check if employee_id is set
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Prepare SQL query CONCAT(employee.FirstName, ' ', COALESCE(employee.LastName, '')) AS FullName
    $sql = "SELECT CONCAT(e.FirstName, ' ', COALESCE(e.LastName, '')) AS FullName, 
               e.Picture, 
               d.Name AS Department, 
               desig.Name AS Designation, 
               desig.Salary 
        FROM employee e  -- ✅ Use the alias consistently
        INNER JOIN department d ON e.Department_id = d.Department_id
        INNER JOIN designation desig ON e.Designation_id = desig.Designation_id
        WHERE e.Employee_id = :employee_id";


    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Return JSON data
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        // No employee found
        header('Content-Type: application/json');
        echo json_encode(["error" => "No employee found"]);
    }
} else {
    // Missing parameter
    header('Content-Type: application/json');
    echo json_encode(["error" => "Missing employee_id parameter"]);
}
?>
