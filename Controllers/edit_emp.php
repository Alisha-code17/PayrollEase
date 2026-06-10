<?php
include '../Database/db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empID = $_POST['employee_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $joiningDate = $_POST['joining_date'];
    //$status = $_POST['status'];
    $username = $_POST['username'];
    $userRole = $_POST['user_role'];
    $errors = [];

    if (!preg_match("/^[a-zA-Z\s]+$/", $firstName)) {
    $errors[] = "First name: only letters and spaces allowed.";
    }
    if (!empty($lastName) && !preg_match("/^[a-zA-Z\s]+$/", $lastName)) {
    $errors[] = "Last name: only letters and spaces allowed.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
    }

    $emailCheck = $conn->prepare("SELECT Employee_id FROM employee WHERE Email = ? AND Employee_id != ?");
    $emailCheck->bind_param("si", $email, $empID);
    $emailCheck->execute();
    $emailCheck->store_result();
    if ($emailCheck->num_rows > 0) {
    $errors[] = "Email already exists.";
    }
    $emailCheck->close();

    if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => implode(" ", $errors)]);
    exit();
    }

    if (!empty($_FILES['picture']['name'])) {
        $pictureName = time() . "_" . basename($_FILES['picture']['name']);
        $targetFile = "uploads/" . $pictureName;
        move_uploaded_file($_FILES['picture']['tmp_name'], $targetFile);

     $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/jpeg'];
      if (!in_array($_FILES['picture']['type'], $allowedTypes)) {
     // echo json_encode(['status' => 'error', 'message' => 'Only JPG, PNG, or WEBP files allowed.']);
     echo json_encode(["status" => "error", "message" => "Invalid file type! Only JPG, JPEG and PNG images are allowed."]);

      exit();
      }
        $updateQuery = "UPDATE employee SET 
            FirstName='$firstName', 
            LastName='$lastName', 
            Email='$email', 
            Phone='$phone', 
            Address='$address', 
            Department_id='$department', 
            Designation_id='$designation', 
            JoiningDate='$joiningDate', 
            Picture='$pictureName' 
            WHERE Employee_id='$empID'";


    } else {
        // Update query without pic
        $updateQuery = "UPDATE employee SET 
            FirstName='$firstName', 
            LastName='$lastName', 
            Email='$email', 
            Phone='$phone', 
            Address='$address', 
            Department_id='$department', 
            Designation_id='$designation', 
            JoiningDate='$joiningDate' 
            WHERE Employee_id='$empID'";
    }

         $updateUserQuery = $conn->prepare("UPDATE users SET Username = ?, UserRole = ? WHERE User_id = ?");
         $updateUserQuery->bind_param("ssi", $username, $userRole, $empID); 
         $updateUserQuery->execute();
         $updateUserQuery->close();

         if ($conn->query($updateQuery)) {
         echo json_encode([
        'status' => 'success',
        'message' => 'Employee updated successfully!'
         ]);
    } else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error updating employee: ' . $conn->error
    ]);
}
exit();
}
?>