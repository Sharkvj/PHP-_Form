<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'management';

// Database connection
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists and verify password
    $stmt = $conn->prepare("SELECT id, username, email, pass_word FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['pass_word'])) {
            echo "<h1>Login Successful!</h1>";
            echo "<h2>All Users:</h2>";

            // Fetch all users
            $allUsers = $conn->query("SELECT id, username, email FROM users");

            if ($allUsers->num_rows > 0) {
                echo "<table class='user-table'>";
                echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Actions</th></tr>";

                while ($row = $allUsers->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>
                            <a href='update.php?id=" . $row['id'] . "' class='btn'>Update</a> |
                            <a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p class='no-users'>No users found.</p>";
            }
        } else {
            echo "<p class='error'>Invalid Password. Please try again.</p>";
        }
    } else {
        echo "<p class='error'>No account found with this email.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Back to Homepage Button -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        h1, h2 {
            text-align: center;
        }
        .user-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .user-table th, .user-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .user-table th {
            background-color: #007BFF;
            color: #fff;
        }
        .btn {
            text-decoration: none;
            color: #fff;
            background-color: #007BFF;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .btn-delete {
            background-color: #DC3545;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .no-users, .error {
            text-align: center;
            font-size: 18px;
            color: #DC3545;
        }
        .back-btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .back-btn {
            background-color: #28A745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .back-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="back-btn-container">
        <a href="index.html" class="back-btn">Back to Homepage</a>
    </div>
</body>
</html>
