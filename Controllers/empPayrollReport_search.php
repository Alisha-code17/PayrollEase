<?php
// Allow cross-origin requests for development
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your MySQLi DB connection
include '../Database/db1.php';

// Check if Employee_id exists in session
if (!isset($_SESSION['Employee_id'])) {
    die("Error: Employee ID is missing in the session.");
}

$employee_id = $_SESSION['Employee_id'];

// Get the search term from POST request
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// SQL query to fetch payroll data for the logged-in employee
$query = "SELECT 
    PayrollMonth,
    YEAR(DateCreated) AS Year,
    PresentDays,
    AbsentDays,
    BasicSalary,
    GrossSalary,
    NetSalary
FROM payroll
WHERE Employee_id = ?";

// Add search condition if search term exists
if (!empty($search)) {
    $query .= " AND (PayrollMonth LIKE ? OR 
                   YEAR(DateCreated) LIKE ?)";
}

// Prepare the query
$stmt = $conn->prepare($query);

// Bind parameters based on whether there's a search term
if (!empty($search)) {
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("iss", $employee_id, $searchTerm, $searchTerm);
} else {
    $stmt->bind_param("i", $employee_id);
}

$stmt->execute();
$result = $stmt->get_result();

$output = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td>
                <input type='checkbox' class='subCheckbox' name='payroll_records[]' 
                value='" . htmlspecialchars($row['PayrollMonth']) . "' 
                style='width:15px;height:15px;'>
              </td>";
        $output .= "<td class='payroll-month' style='padding-left:20px;'>" . htmlspecialchars($row['PayrollMonth']) . "</td>";
        $output .= "<td class='payroll-year' style='padding-left:20px;'>" . htmlspecialchars($row['Year']) . "</td>";
        $output .= "<td class='present-days' style='padding-left:20px;'>" . htmlspecialchars($row['PresentDays']) . "</td>";
        $output .= "<td class='absent-days' style='padding-left:20px;'>" . htmlspecialchars($row['AbsentDays']) . "</td>";
        $output .= "<td class='basic-salary' style='padding-left:20px;'>Rs. " . htmlspecialchars($row['BasicSalary']) . "</td>";
        $output .= "<td class='gross-salary' style='padding-left:20px;'>Rs. " . htmlspecialchars($row['GrossSalary']) . "</td>";
        $output .= "<td class='net-salary' style='padding-left:20px;'>Rs. " . htmlspecialchars($row['NetSalary']) . "</td>";
        $output .= "</tr>";
    }
} else {
    $output = "<tr><td colspan='8'>No matching payroll records found.</td></tr>";
}

echo $output;

$stmt->close();
$conn->close();
?>