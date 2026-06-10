<?php
include '../Database/db1.php'; // Changed to MySQLi connection

header('Content-Type: application/json');

try {
    // IDENTICAL validation check
    if (!isset($_GET['empid']) || empty($_GET['empid'])) {
        throw new Exception('Employee ID not provided');
    }

    $employeeId = $_GET['empid'];
    
    // Same query, just parameter syntax changed
    $stmt = $conn->prepare("SELECT JoiningDate FROM employee WHERE Employee_id = ?");
    $stmt->bind_param("i", $employeeId); // "i" for integer
    $stmt->execute();
    
    // Same result processing with different method names
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result) {
        // EXACT SAME response structure
        $response = [
            'status' => 'success',
            'joiningDate' => $result['JoiningDate']
        ];
    } else {
        // IDENTICAL not-found response
        $response = [
            'status' => 'error',
            'message' => 'Employee not found'
        ];
    }
    
    echo json_encode($response);
    
} catch (mysqli_sql_exception $e) {
    // Same error format for database errors
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Same general error handling
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Close connections (only structural difference)
$stmt->close();
$conn->close();
?>