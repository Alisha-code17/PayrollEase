<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
header("Content-Type: application/json");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read JSON input
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Debugging: Log received JSON data
    error_log("Received Data: " . print_r($inputData, true));

    // Extract data using correct keys
    $empid = $inputData["empid"] ?? "";
    $presentDays = $inputData["presentdays"] ?? "";
    $absentDays = $inputData["absentdays"] ?? "";
    $overtimeHours = isset($inputData["overtimehours"]) && $inputData["overtimehours"] !== "" ? $inputData["overtimehours"] : null; // Allow NULL
    $month = $inputData["month"] ?? "";
    $year = $inputData["year"] ?? "";
    $isNewEmployee = $inputData["isNewEmployee"] ?? false;

    // Validate required fields (except overtime)
    if (empty($empid) || empty($presentDays) || empty($month) || empty($year)) {
        echo json_encode(["success" => false, "message" => "All required fields must be filled!"]);
        exit;
    }

    try {
        // Skip previous month check for new employees (joined in current month)
        if (!$isNewEmployee) {
            // Check if previous month's attendance exists and is not zero
            $currentMonthNum1 = date('n', strtotime($month));
            $previousMonthNumC = $currentMonthNum1 ;
            $previousYear = $year;
            
            if ($previousMonthNumC == 0) {
                $previousMonthNumC = 12;
                $previousYear = $year - 1;
            }
            
            $previousMonthName = date('F', mktime(0, 0, 0, $previousMonthNumC - 1, 1));
            
            $checkSql = "SELECT Present_days FROM attendance 
                        WHERE Employee_id = :empid 
                        AND Month = :month 
                        AND Year = :year";
            
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':empid', $empid);
            $checkStmt->bindParam(':month', $previousMonthName);
            $checkStmt->bindParam(':year', $previousYear);
            $checkStmt->execute();
            
            $previousAttendance = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            /*if (!$previousAttendance) {
                // Previous month attendance doesn't exist
                echo json_encode([
                    "success" => false, 
                    "message" => "Previous month ($previousMonthName $previousYear) attendance not found for this employee. Current month attendance cannot be marked.",
                    "forceZero" => true
                ]);
                exit;
            } elseif ($previousAttendance['Present_days'] == 0) {
                // Previous month attendance exists but is zero
                echo json_encode([
                    "success" => false, 
                    "message" => "Previous month ($previousMonthName $previousYear) attendance is marked as 0. Current month attendance cannot be marked.",
                    "forceZero" => true
                ]);
                exit;
            }*/
        }

        // Check if attendance for this month already exists
        $checkExistingSql = "SELECT * FROM attendance 
                           WHERE Employee_id = :empid 
                           AND Month = :month 
                           AND Year = :year";
        $checkExistingStmt = $conn->prepare($checkExistingSql);
        $checkExistingStmt->bindParam(':empid', $empid);
        $checkExistingStmt->bindParam(':month', $month);
        $checkExistingStmt->bindParam(':year', $year);
        $checkExistingStmt->execute();
        
        if ($checkExistingStmt->rowCount() > 0) {
            echo json_encode([
                "success" => false, 
                "message" => "Attendance for $month $year has already been marked for this employee."
            ]);
            exit;
        }

        // Insert query using PDO
        $sql = "INSERT INTO attendance (Employee_id, Present_days, Absent_days, Overtime_hours, Month, Year)
                VALUES (:empid, :presentdays, :absentdays, :overtimehours, :month, :year)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':empid', $empid);
        $stmt->bindParam(':presentdays', $presentDays);
        $stmt->bindParam(':absentdays', $absentDays);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':year', $year);

        // Handle NULL for overtime
        if ($overtimeHours === null) {
            $stmt->bindValue(':overtimehours', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':overtimehours', $overtimeHours);
        }

        if ($stmt->execute()) {
            $message = "Attendance marked successfully!";
            if ($isNewEmployee) {
                $message = "Attendance marked successfully for new employee!";
            }
            echo json_encode(["success" => true, "message" => $message]);
        } else {
            echo json_encode(["success" => false, "message" => "Database Error: Failed to insert data"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
    }
}
?>