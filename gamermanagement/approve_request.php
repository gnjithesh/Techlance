<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
    header("location: login.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Perform database operations to approve the request and update the user's access type
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "gamer_management";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Update the status of the access request to "Approved"
    $update_request_query = "UPDATE access_requests SET status = 'Approved' WHERE id = $request_id";
    if ($connection->query($update_request_query) === TRUE) {
        // Update the user's access type to "elevated"
        $user_id = $_GET['user_id'];
        $update_user_query = "UPDATE users SET accesstype = 'elevated' WHERE id = $user_id";
        if ($connection->query($update_user_query) === TRUE) {
            echo "Request approved successfully.";
        } else {
            echo "Error updating user's access type: " . $connection->error;
        }
    } else {
        echo "Error approving request: " . $connection->error;
    }

    $connection->close();
}
?>