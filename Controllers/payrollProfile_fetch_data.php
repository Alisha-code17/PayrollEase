<?php
include '../Database/db1.php'; // MySQLi database connection

$sql = "SELECT PayrollProfile_ID, Allowance1, Allowance1_Amount, Allowance2, Allowance2_Amount,
Allowance3, Allowance3_Amount, Deduction1, Deduction1_Amount, Deduction2, Deduction2_Amount,
Deduction3, Deduction3_Amount FROM payrollprofile";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td style="text-align: center;">' . $row["PayrollProfile_ID"] . '</td>';
        
        // Allowances
        echo '<td style="text-align: center;">' . (!empty($row["Allowance1"]) ? htmlspecialchars($row["Allowance1"]) : '--') . '</td>';
        echo '<td style="text-align: center;">' . (($row["Allowance1_Amount"] != 0) ? htmlspecialchars($row["Allowance1_Amount"]) : '--') . '</td>';
        
        echo '<td style="text-align: center;">' . (!empty($row["Allowance2"]) ? htmlspecialchars($row["Allowance2"]) : '--') . '</td>';
        echo '<td style="text-align: center;">' . (($row["Allowance2_Amount"] != 0) ? htmlspecialchars($row["Allowance2_Amount"]) : '--') . '</td>';
        
        echo '<td style="text-align: center;">' . (!empty($row["Allowance3"]) ? htmlspecialchars($row["Allowance3"]) : '--') . '</td>';
        echo '<td style="text-align: center;">' . (($row["Allowance3_Amount"] != 0) ? htmlspecialchars($row["Allowance3_Amount"]) : '--') . '</td>';
        
        // Deductions
        echo '<td style="text-align: center;">' . (!empty($row["Deduction1"]) ? htmlspecialchars($row["Deduction1"]) : '--') . '</td>';
        echo '<td style="text-align: center;">' . (($row["Deduction1_Amount"] != 0) ? htmlspecialchars($row["Deduction1_Amount"]) : '--') . '</td>';
        
        echo '<td style="text-align: center;">' . (!empty($row["Deduction2"]) ? htmlspecialchars($row["Deduction2"]) : '--') . '</td>';
        echo '<td style="text-align: center;">' . (($row["Deduction2_Amount"] != 0) ? htmlspecialchars($row["Deduction2_Amount"]) : '--') . '</td>';
        
        echo '<td style="text-align: center;">' . (!empty($row["Deduction3"]) ? htmlspecialchars($row["Deduction3"]) : '--') . '</td>';
        echo '<td style="text-align: center;">' . (($row["Deduction3_Amount"] != 0) ? htmlspecialchars($row["Deduction3_Amount"]) : '--') . '</td>';
        
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="13" style="text-align: center;">No records found.</td></tr>';
}

$conn->close();
?>
