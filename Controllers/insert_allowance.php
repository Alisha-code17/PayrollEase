<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Important for AJAX JSON response

include '../Database/db1.php'; // MySQLi database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $amount = trim($_POST['amount'] ?? '');

        // Check if any field is empty
        if (empty($name) || empty($description) || empty($amount)) {
            echo json_encode([
                'status' => 'error',
                'message' => '⚠️ Error: All fields are required!'
            ]);
            exit;
        }

        // Prepare SQL Query
        $sql = "INSERT INTO salaryextras (Name, Description, Amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind Parameters
        $stmt->bind_param("ssi", $name, $description, $amount);

        // Execute the Query
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Record inserted successfully!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inserting record!'
            ]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'SQL Error: ' . $e->getMessage()
        ]);
    }
    $conn->close();
}
?>