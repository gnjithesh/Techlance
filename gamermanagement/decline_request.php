<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
    header("location: login.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Perform database operation to decline the request
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "gamer_management";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Update the status of the access request to "Declined"
    $update_request_query = "UPDATE access_requests SET status = 'Denied' WHERE id = $request_id";
    if ($connection->query($update_request_query) === TRUE) {
        echo "Request declined successfully.";
    } else {
        echo "Error declining request: " . $connection->error;
    }

    $connection->close();
}
?>