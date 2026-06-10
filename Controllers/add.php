<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../Database/db1.php'; 

    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]);
        exit();
    }
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = isset($_POST['last_name']) ? mysqli_real_escape_string($conn, $_POST['last_name']) : null;
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $joiningDate = mysqli_real_escape_string($conn, $_POST['joining_date']);
    $deptId = mysqli_real_escape_string($conn, $_POST['department']);
    $designationId = mysqli_real_escape_string($conn, $_POST['designation']);
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $userRole = mysqli_real_escape_string($conn, $_POST['user_role']);

    $namePattern = "/^[A-Za-z\s]+$/";
    if (!preg_match($namePattern, $firstName)) {
    echo json_encode(["status" => "error", "message" => "First name should contain only letters and spaces."]);
    exit();
    }
    if (!empty($lastName) && !preg_match($namePattern, $lastName)) {
    echo json_encode(["status" => "error", "message" => "Last name should contain only letters and spaces."]);
    exit();
    }
    $phone = $_POST['phone']; 
    $cleanedPhone = preg_replace('/[\s\-]/', '', $phone);
    if (substr($cleanedPhone, 0, 3) === "+92") {
    $cleanedPhone = "0" . substr($cleanedPhone, 3);
    }
    $phonePattern = "/^\d{11}$/";
    if (!preg_match($phonePattern, $cleanedPhone)) {
        echo json_encode(["status" => "error", "message" => "Please enter a valid phone number."]);
    exit();
    }
    $today = date("Y-m-d");
    if (!empty($joiningDate) && $joiningDate > $today) {
        echo json_encode(["status" => "error", "message" => "Joining date cannot be in the future!"]);
        exit();
    }
    if (!empty($address)) {
        if (!preg_match('/^[a-zA-Z0-9\s,.\-\/#]{5,}$/', $address)) {
            echo json_encode([
                "status" => "error",
                "message" => "Please enter a valid address (min 5 characters, no special symbols like @ or $)."
            ]);
            exit();
        }
    }
    if ($password !== $confirmPassword) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match!"]);
        exit();
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{5,}$/', $password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Password must be at least 5 characters long and contain at least 1 uppercase letter and 1 number."
        ]);
        exit();
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkUsernameQuery = "SELECT * FROM Users WHERE Username = '$username'";
    $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);
    if (mysqli_num_rows($checkUsernameResult) > 0) {
        echo json_encode(["status" => "error", "message" => "This username is already taken. Please choose another one."]);
        exit();
    }

    $checkEmailQuery = "SELECT * FROM employee WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "This email is already registered. Please use a different one."]);
        exit();
    }

   /* $imageName = "default.jpg";
    if (!empty($_FILES['avatar']['name'])) {
        $imageName = time() . "_" . basename($_FILES["avatar"]["name"]);
        $targetDir = "uploads/";
        $targetFilePath = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $allowedTypes)) {
            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath)) {
                $imageName = "default.jpg";
            }
        } else {
            $imageName = "default.jpg";
        }
    }*/

        $imageName = "default.jpg";
if (!empty($_FILES['avatar']['name'])) {
    $targetDir = "uploads/";
    $fileTmp = $_FILES["avatar"]["tmp_name"];
    $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    $fileExt = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $fileType = mime_content_type($fileTmp);

    $allowedExtensions = ["jpg", "jpeg", "png"];
    $allowedMimeTypes = ["image/jpeg", "image/png"];

    if (in_array($fileExt, $allowedExtensions) && in_array($fileType, $allowedMimeTypes)) {
        if (move_uploaded_file($fileTmp, $targetFilePath)) {
            $imageName = $fileName; // Only if upload successful
        } else {
            echo json_encode(["status" => "error", "message" => "File upload failed!"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid file type! Only JPG, JPEG and PNG images are allowed."]);
        exit();
    }
}

    $userQuery = "INSERT INTO Users (Username, Password, UserRole) VALUES ('$username', '$hashedPassword', '$userRole')";
    if (mysqli_query($conn, $userQuery)) {
        $userId = mysqli_insert_id($conn);

        $employeeQuery = "INSERT INTO employee (User_id, department_id, designation_id, picture, FirstName, LastName, email, phone, address, joiningDate, status) 
                          VALUES ('$userId', '$deptId', '$designationId', '$imageName', '$firstName', '$lastName', '$email', '$phone', '$address', '$joiningDate', 'Active')";

        if (mysqli_query($conn, $employeeQuery)) {
            echo json_encode(["status" => "success", "message" => "Employee added successfully!"]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error (Employee): " . mysqli_error($conn)]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error (Users): " . mysqli_error($conn)]);
        exit();
    }
    mysqli_close($conn);
}
?>