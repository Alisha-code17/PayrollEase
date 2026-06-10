<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../Database/db1.php';
    $username = trim($_POST["username"]);
    
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "This username is already taken. Please choose another one.";
    } else {
        echo ""; 
    }
    $stmt->close();
    $conn->close();
}
?>