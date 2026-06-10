<?php
session_start();
include '../Database/db1.php';

$response = ["status" => "error", "message" => "Something went wrong!"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT u.User_id, u.Username, u.Password, u.UserRole, e.Employee_id, e.Status
                  FROM users u
                  LEFT JOIN employee e ON u.User_id = e.User_id
                  WHERE u.Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if ($password === $user['Password'] || password_verify($password, $user['Password'])) {
                $_SESSION['username'] = $user['Username'];
                $_SESSION['type'] = $user['UserRole'];
                $_SESSION['user_id'] = $user['User_id'];
                $_SESSION['Employee_id'] = $user['Employee_id'];

                if ($user['UserRole'] === 'Admin') {
                    // Employees must be Active
                        if ($user['Status'] === 'Active') {
                            $response = ["status" => "success", "role" => "Admin"];
                    } else {
                        $response = ["status" => "error", "message" => "Your account is deactivated. You can't access the Dashboard."];
                        session_destroy(); // prevent session creation for deactivated users
                    }
                } elseif ($user['UserRole'] === "Employee") {
                    // Employees must be Active
                        if ($user['Status'] === 'Active') {
                            $response = ["status" => "success", "role" => "Employee"];
                    } else {
                        $response = ["status" => "error", "message" => "Your account is deactivated. You can't access the Dashboard."];
                        session_destroy(); // prevent session creation for deactivated users
                    }
                }
            } else {
                $response = ["status" => "error", "message" => "Invalid username or password!"];
            }
        } else {
            $response = ["status" => "error", "message" => "User not found!"];
        }
        $stmt->close();
    } else {
        $response = ["status" => "error", "message" => "Please fill in both username and password."];
    }
}

$conn->close();
echo json_encode($response);
exit;
?>