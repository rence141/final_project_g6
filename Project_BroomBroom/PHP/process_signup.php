<?php
// Include database connection file
include_once "db.php"; // Ensure this points to your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables with default empty values
    $firstName = htmlspecialchars($_POST['FirstName'] ?? '');
    $lastName = htmlspecialchars($_POST['LastName'] ?? '');
    $gender = htmlspecialchars($_POST['Gender'] ?? '');  // Gender is passed as a form field
    $email = strtolower(trim($_POST['Email'] ?? ''));
    $password = $_POST['Password'] ?? ''; 
    $number = htmlspecialchars($_POST['Number'] ?? ''); 
    $language = htmlspecialchars($_POST['Language'] ?? '');
    $usertype = htmlspecialchars($_POST['UserType'] ?? ''); // New UserType column

    // Map gender to 'M', 'F', or 'O' based on form selection
    if ($gender === 'Male') {
        $gender = 'M';
    } elseif ($gender === 'Female') {
        $gender = 'F';
    } elseif ($gender === 'Other') {
        $gender = 'O';
    }

    // Check if all required fields are not empty
    if (!empty($firstName) && !empty($lastName) && !empty($gender) && !empty($email) && !empty($password) && !empty($number) && !empty($usertype)) {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
        } 
        // Validate password length (at least 8 characters)
        else if (strlen($password) < 8) {
            echo "Password must be at least 8 characters long.";
        }
        // Validate phone number (10-15 digits)
        else if (!preg_match('/^\d{10,15}$/', $number)) {
            echo "Invalid phone number format.";
        }
        // Validate UserType (only 'admin' or 'user' allowed)
        else if (!in_array($usertype, ['admin', 'user'])) {
            echo "Invalid user type. Please select either 'admin' or 'user'.";
        }
        else {
            // Check if email already exists in the database
            $sql = "SELECT * FROM signup WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Email already exists
                echo "The email is already registered. Please use a different email.";
            } else {
                // Insert the new user record using a prepared statement
                $stmt = $conn->prepare("INSERT INTO signup (first_name, last_name, gender, email, password, number, language, UserType) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $firstName, $lastName, $gender, $email, $password, $number, $language, $usertype);

                if ($stmt->execute()) {
                    echo "Registration successful!";
                } else {
                    echo "Error: " . $stmt->error;
                }
            }

            $stmt->close(); // Close the prepared statement
        }
    } else {
        echo "All required fields must be filled out.";
    }

    $conn->close(); // Close the database connection
}
?>
