<?php
// Include the database connection
include_once "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form inputs
    $email = trim($_POST['Email']);
    $inputPassword = trim($_POST['Password']);
    $selectedUserType = trim($_POST['UserType']);

    // Query to retrieve user data from 'signup' table
    $sql = "SELECT * FROM signup WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if ($inputPassword === $user['password']) {
            // Get the UserType from signup table (Admin or User)
            $actualUserType = $user['UserType'];

            // Insert data into the 'user_form' table if user is not already registered
            $insertSql = "INSERT INTO user_form (email, password, UserType) 
                          SELECT ?, ?, ? 
                          FROM DUAL 
                          WHERE NOT EXISTS (SELECT 1 FROM user_form WHERE email=?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssss", $email, $inputPassword, $actualUserType, $email);
            $insertStmt->execute();

            // Store session data
            $_SESSION['email'] = $user['email'];
            $_SESSION['UserType'] = $actualUserType;

            // Redirect based on UserType
            if ($actualUserType === 'Admin') {
                // Admin user can access both Admin and User pages
                if ($selectedUserType === 'Admin') {
                    echo "<script>alert('Welcome Admin! Redirecting to Admin Page.'); window.location.href = '/Project_BroomBroom/PHP/ecommerce_admin.php';</script>";
                } elseif ($selectedUserType === 'User') {
                    echo "<script>alert('Welcome Admin! Redirecting to User Page.'); window.location.href = '/Project_BroomBroom/PHP/ecommerce_user.php';</script>";

                }
                exit();
            } 
            // User can only access User page
            elseif ($actualUserType === 'User') {
                // User can only access the user page
                if ($selectedUserType === 'User') {
                    echo "<script>alert('Welcome User! Redirecting to User Page.'); window.location.href = '/Project_BroomBroom/PHP/ecommerce_user.php';</script>";
                    exit();
                } else {
                    // Restrict access for User if attempting to access Admin page
                    echo "<script>alert('Access Restricted: Regular users cannot access the Admin page.'); window.history.back();</script>";
                    exit();
                }
            }
        } else {
            // Password is incorrect
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        // No user found with the given email
        echo "<script>alert('No user found with this email.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
