<?php
/**
 * sendmail.php - Payslip Email Sender
 * 
 * Handles generation and emailing of employee payslips
 * Requires: PHPMailer, TCPDF, MySQL database connection
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer dependencies
require '../Resources/PHPMailer-master/src/Exception.php';
require '../Resources/PHPMailer-master/src/PHPMailer.php';
require '../Resources/PHPMailer-master/src/SMTP.php';

// Set JSON response header
header('Content-Type: application/json');

// Debugging: Log received POST data
error_log("[".date('Y-m-d H:i:s')."] Payslip request received. POST data: " . print_r($_POST, true));

try {
    // ======================================================================
    // SECTION 1: INPUT VALIDATION AND DATA PREPARATION
    // ======================================================================
    
    /**
     * Get POST data with flexible field name support
     * Supports both direct form submissions and AJAX requests
     */
    $employeeID = $_POST['emp_ID_payslip'] ?? $_POST['selectedEmpID'] ?? null;
    $month = $_POST['payrollMonth'] ?? $_POST['selectedMonthHidden'] ?? null;
    $year = $_POST['selectedYearHidden'] ?? null;

    // Validate required inputs
    if (!$employeeID || !$month || !$year) {
        $receivedData = [
            'EmployeeID' => $employeeID ?? 'null',
            'Month' => $month ?? 'null',
            'Year' => $year ?? 'null'
        ];
        throw new Exception('Missing required payslip data. Received: ' . json_encode($receivedData));
    }

    // ======================================================================
    // SECTION 2: DATABASE OPERATIONS
    // ======================================================================
    
    // Database configuration
    include '../Database/db1.php';
    // Set charset to ensure proper encoding
    $conn->set_charset("utf8mb4");

    /**
     * Fetch employee details
     * Includes: Email, FirstName, LastName
     */
    $employeeQuery = "SELECT Email, FirstName, LastName FROM employee WHERE Employee_id = ?";
    $stmt = $conn->prepare($employeeQuery);
    if (!$stmt) {
        throw new Exception("Employee query prepare failed: {$conn->error}");
    }
    
    $stmt->bind_param("s", $employeeID);
    if (!$stmt->execute()) {
        throw new Exception("Employee query execute failed: {$stmt->error}");
    }
    
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Employee record not found for ID: $employeeID");
    }
    
    $employee = $result->fetch_assoc();
    $employeeEmail = $employee['Email'];
    $employeeName = "{$employee['FirstName']} {$employee['LastName']}";

    /**
     * Fetch payroll data
     * Includes: BasicSalary, Allowances, Deductions, NetSalary
     */
    $payrollQuery = "
        SELECT BasicSalary, TotalAllowance, TotalDeductions, NetSalary 
        FROM payroll 
        WHERE Employee_id = ? AND PayrollMonth = ? AND Year = ?
    ";
    $stmt = $conn->prepare($payrollQuery);
    if (!$stmt) {
        throw new Exception("Payroll query prepare failed: {$conn->error}");
    }
    
    $stmt->bind_param("sss", $employeeID, $month, $year);
    if (!$stmt->execute()) {
        throw new Exception("Payroll query execute failed: {$stmt->error}");
    }
    
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("No payroll data found for $employeeName ($employeeID) for $month $year");
    }
    
    $payrollData = $result->fetch_assoc();

    // ======================================================================
    // SECTION 3: PDF GENERATION
    // ======================================================================
    
    // Load TCPDF library
    require_once('../Resources/TCPDF/tcpdf.php');
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('PayrollEase System');
    $pdf->SetAuthor('HR Department');
    $pdf->SetTitle("Payslip for $employeeName - $month $year");
    $pdf->SetSubject('Employee Payslip');
    
    // Add a page
    $pdf->AddPage();
    
    // Set font and create header
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, "PayrollEase", 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, "PAYSLIP - $month $year", 0, 1, 'C');
    $pdf->Ln(10);
    
    // Employee information section
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "Employee: $employeeName", 0, 1);
    $pdf->Cell(0, 10, "Employee ID: $employeeID", 0, 1);
    $pdf->Cell(0, 10, "Pay Period: $month $year", 0, 1);
    $pdf->Ln(10);
    
    // Payroll details section
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(100, 10, "Earnings", 0, 0);
    $pdf->Cell(0, 10, "Amount", 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    $pdf->Cell(100, 10, "Basic Salary", 0, 0);
    $pdf->Cell(0, 10, "Rs." . number_format($payrollData['BasicSalary'], 2), 0, 1);
    
    $pdf->Cell(100, 10, "Allowances", 0, 0);
    $pdf->Cell(0, 10, "Rs." . number_format($payrollData['TotalAllowance'], 2), 0, 1);
    
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(100, 10, "Gross Earnings", 0, 0);
    $pdf->Cell(0, 10, "Rs." . number_format($payrollData['BasicSalary'] + $payrollData['TotalAllowance'], 2), 0, 1);
    $pdf->Ln(10);
    
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(100, 10, "Deductions", 0, 0);
    $pdf->Cell(0, 10, "Amount", 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    $pdf->Cell(100, 10, "Total Deductions", 0, 0);
    $pdf->Cell(0, 10, "Rs." . number_format($payrollData['TotalDeductions'], 2), 0, 1);
    $pdf->Ln(10);
    
    // Net pay section
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(100, 10, "NET PAY", 0, 0);
    $pdf->Cell(0, 10, "Rs." . number_format($payrollData['NetSalary'], 2), 0, 1);
    $pdf->Ln(15);
    
    // Footer
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, "Generated on: " . date('F j, Y, g:i a'), 0, 1, 'C');
   
    
    // Generate PDF content as string
    $pdfData = $pdf->Output('payslip.pdf', 'S');

    // ======================================================================
    // SECTION 4: EMAIL SENDING
    // ======================================================================
    
    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'EMAIL'; // Replace with your email
    $mail->Password = 'APP_PASSWORD';    // Replace with App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->Debugoutput = function($str, $level) {
        error_log("[SMTP] $str");
    };
    
    // Email content configuration
    $mail->setFrom('hr@yourcompany.com', 'PayrollEase');
    $mail->addAddress($employeeEmail, $employeeName);
    $mail->addReplyTo('PayrollEase@gmail.com', 'Admin Department');
    $mail->Subject = "Your Payslip for $month $year";
    
    // HTML email body
    $mail->isHTML(true);
    $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .payslip-container { max-width: 600px; margin: 0 auto; }
                .header { text-align: center; margin-bottom: 20px; }
                .details { margin-bottom: 20px; }
                .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .summary-table th, .summary-table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                .summary-table th { background-color: #f2f2f2; }
                .footer { margin-top: 20px; font-size: 0.9em; color: #666; text-align: center; }
            </style>
        </head>
        <body>
            <div class='payslip-container'>
                <div class='header'>
                    <h2>Your Payslip for $month $year</h2>
                </div>
                
                <div class='details'>
                    <p>Dear $employeeName,</p>
                    <p>Please find attached your payslip for $month $year.</p>
                </div>
                
                <table class='summary-table'>
                    <tr><th colspan='2'>Payment Summary</th></tr>
                    <tr>
                        <td><strong>Basic Salary:</strong></td>
                        <td>Rs." . number_format($payrollData['BasicSalary'], 2) . "</td>
                    </tr>
                    <tr>
                        <td><strong>Allowances:</strong></td>
                        <td>Rs" . number_format($payrollData['TotalAllowance'], 2) . "</td>
                    </tr>
                    <tr>
                        <td><strong>Deductions:</strong></td>
                        <td>Rs" . number_format($payrollData['TotalDeductions'], 2) . "</td>
                    </tr>
                    <tr class='total'>
                        <td><strong>Net Salary:</strong></td>
                        <td><strong>Rs" . number_format($payrollData['NetSalary'], 2) . "</strong></td>
                    </tr>
                </table>
                
                <div class='footer'>
                    <p>Contact Admin department for any Query.</p>
                </div>
            </div>
        </body>
        </html>
    ";
    
    // Plain text alternative for non-HTML email clients
    $mail->AltBody = "Payslip Notification\n\n" .
                     "Dear $employeeName,\n\n" .
                     "Your payslip for $month $year is attached.\n\n" .
                     "Basic Salary: Rs." . number_format($payrollData['BasicSalary'], 2) . "\n" .
                     "Allowances: Rs." . number_format($payrollData['TotalAllowance'], 2) . "\n" .
                     "Deductions: Rs." . number_format($payrollData['TotalDeductions'], 2) . "\n" .
                     "Net Salary: Rs." . number_format($payrollData['NetSalary'], 2) . "\n\n" .
                     "This is an automatically generated email.";
    
    // Attach the PDF
    $mail->addStringAttachment($pdfData, "Payslip_{$employeeID}_{$month}_{$year}.pdf");

    
    // Send the email
    if ($mail->send()) {
        error_log("[".date('Y-m-d H:i:s')."] Payslip sent successfully to $employeeEmail");
        echo json_encode([
            'success' => true,
            'message' => 'Payslip has been sent successfully'
        ]);
        exit;
    }  

} catch (Exception $e) {
    // If there was an error
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>