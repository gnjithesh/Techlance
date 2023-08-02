<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "gamer_management";

        $connection = new mysqli($servername, $username, $password, $database);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        // Check if the user with the given ID exists
        $check_query = "SELECT id FROM users WHERE id = ?";
        $stmt = $connection->prepare($check_query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // User with the given ID exists, proceed with deletion
            $delete_query = "DELETE FROM users WHERE id = ?";
            $stmt = $connection->prepare($delete_query);
            $stmt->bind_param('i', $user_id);

            if ($stmt->execute()) {
                // Deletion successful, redirect to the user list page
                header("Location: /gamermanagement/users.php");
                exit();
            } else {
                // Deletion failed, you can redirect to an error page or display an error message
                echo "Error deleting user: " . $stmt->error;
            }
        } else {
            // User with the given ID does not exist, you can redirect to an error page or display an error message
            echo "User not found.";
        }

        $stmt->close();
        $connection->close();
    }
}
?>
