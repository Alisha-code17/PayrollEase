<?php
header('Content-Type: application/json');
require '../Database/db1.php'; // Make sure this file has a PDO connection

try {
    // Prepare the query to fetch unmarked employees
    $query = "SELECT e.Employee_id, 
                 CONCAT(COALESCE(e.FirstName, ''), ' ', COALESCE(e.LastName, '')) AS EmployeeName 
          FROM employee e 
          WHERE e.Status = 'active' 
          AND NOT EXISTS (
              SELECT 1 FROM attendance a 
              WHERE a.Employee_id = e.Employee_id 
              AND a.Month = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
              AND a.Year = YEAR(CURRENT_DATE)
          )";

    
    // Execute query using PDO
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Fetch results
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debugging: Show Output
    echo json_encode($employees, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
