<?php
include '../Database/db1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    $query = "SELECT * FROM employee WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This email is already registered. Please use a different one.";
    } else {
        echo "";
    }
    $stmt->close();
    $conn->close();
}
?>