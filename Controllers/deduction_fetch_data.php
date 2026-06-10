<?php
include '../Database/db1.php'; // MySQLi database connection

// SQL query to fetch data from salarydeductions table (unchanged)
$sql = "SELECT SalaryDeductions_id, Name, Description, Amount FROM salarydeductions";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Loop through each row and render HTML for each dynamically
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td style="text-align: center;">' . $row["SalaryDeductions_id"] . '</td>';
        echo '<td>' . htmlspecialchars($row["Name"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["Description"]) . '</td>';
        echo '<td style="text-align: center;">' . number_format($row["Amount"]) . '</td>';
        // Action buttons
        echo '<td style="text-align: center;">';
        echo '<div style="display: flex; justify-content: center; gap: 5px;">';
        
        // Edit Button
        echo '<button class="btn btn-primary edit-deduction-btn" title="Edit" data-toggle="modal" data-target="#editDeductionModal"
                data-id="' . $row["SalaryDeductions_id"] . '" 
                data-name="' . htmlspecialchars($row["Name"]) . '"
                data-description="' . htmlspecialchars($row["Description"]) . '"
                data-amount="' . $row["Amount"] . '"
                style="background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; height:31px; padding-top:2px; width:23px;">
                <img src="../Resources/img/icons8-edit-23.png" alt="Edit">
              </button>';
        
        // Delete Button (you can implement this similarly)
        echo '<button class="btn btn-secondary delete-deduction-btn" title="Delete" data-toggle="modal" data-target="#deleteDeductionModal"
                data-id="' . $row["SalaryDeductions_id"] . '"
                data-name="' . htmlspecialchars($row["Name"]) . '"
                style="background-color:rgba(0,123,255,0); border:none; outline:none; box-shadow:none; height:31px; width:50px; padding-top:2px;">
                <img src="../Resources/img/icon2.png" alt="Delete">
              </button>';
        
        echo '</div>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">No records found.</td></tr>';
}

$conn->close(); // Close connection
?>