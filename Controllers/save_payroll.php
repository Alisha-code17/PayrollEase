<?php
include '../Database/db1.php'; // MySQLi database connection

header('Content-Type: application/json'); // Ensure response is in JSON format

// Read the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if database connection exists
if (!isset($conn)) {
    echo json_encode(["success" => false, "message" => "Database connection error!"]);
    exit;
}

// Check if all required fields are present
if (
    isset($data['empID'], $data['payrollProfileID'], $data['presentDays'], 
          $data['absentDays'], $data['totalAllowance'], $data['totalDeduction'], 
          $data['basicSalary'], $data['bonusAmount'], $data['grossSalary'], 
          $data['netSalary'], $data['payrollMonth'], $data['payrollYear'])
) {
    try {
        // Extract and sanitize values
        $employee_id = intval($data['empID']);
        $payroll_profile_id = intval($data['payrollProfileID']);
        $present_days = intval($data['presentDays']);
        $absent_days = intval($data['absentDays']);
        $total_allowance = intval($data['totalAllowance']);
        $total_deductions = intval($data['totalDeduction']);
        $basic_salary = intval($data['basicSalary']);
        $bonus = intval($data['bonusAmount']);
        $gross_salary = intval($data['grossSalary']);
        $net_salary = intval($data['netSalary']);
        $payroll_month = $data['payrollMonth']; // String
        $payroll_year = intval($data['payrollYear']);

        // Get current timestamp
        $date_created = date("Y-m-d H:i:s");

        // Prepare the SQL statement using MySQLi
        $sql = "INSERT INTO payroll 
                (Employee_id, PayrollProfile_ID, PresentDays, AbsentDays, TotalAllowance, TotalDeductions, 
                 BasicSalary, Bonus, GrossSalary, NetSalary, PayrollMonth, Year, DateCreated)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param(
            "iiiiiiiiiisis",
            $employee_id,
            $payroll_profile_id,
            $present_days,
            $absent_days,
            $total_allowance,
            $total_deductions,
            $basic_salary,
            $bonus,
            $gross_salary,
            $net_salary,
            $payroll_month,
            $payroll_year,
            $date_created
        );

        // Execute statement
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "✅ Payroll record saved successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "❌ Error inserting data!", "error" => $stmt->error]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "❌ Database error!", "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "⚠️ Error: All fields are required!"]);
}

$conn->close();
?>