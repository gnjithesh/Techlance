<?php   
session_start();  

$servername = "localhost";
$username = "root";
$password = "";
$database = "gamer_management";

$connection = new mysqli($servername, $username, $password, $database);
if($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
  
    $stmt = $connection->prepare("SELECT * FROM users WHERE email = ? && password= ?");
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['USER_ID']=$user['id'];  
        $_SESSION['USER_EMAIL']=$user['email'];
        $_SESSION['USER_FIRSTNAME'] = $user['firstname'];
        $_SESSION['USER_LASTNAME'] = $user['lastname'];
        $_SESSION['USER_ACCESSTYPE'] = $user['accesstype'];

        if ($_SESSION['USER_ACCESSTYPE'] == 'Admin') {
            header("location:admin_dashboard.php"); 
        } elseif ($_SESSION['USER_ACCESSTYPE'] == 'elevated') {
            header("location:elevated_user_dashboard.php");
        } else {
            header("location:user_dashboard.php");
        } 
    } else {
        $message ="Please Enter Valid details !";  
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Include Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Login</h2>

    <form action="/gamermanagement/login.php" method="post">
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-semibold mb-1">Email Address</label>
        <input type="email" id="email" name="email" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
        <input type="password" id="password" name="password" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mt-6">
        <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Login</button>
      </div>
      <?php if (isset($message)): ?>
        <div class="mt-4 text-red-500"><?php echo $message; ?></div>
      <?php endif; ?>
    </form>
    <div class="mt-4">
      <a href="/gamermanagement/signup.php" class="text-blue-500 hover:underline">Create an account</a>
    </div>
  </div>
</body>

</html>

