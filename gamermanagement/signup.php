<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gamer_management";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $userPassword = $_POST['password'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $dateofbirth = $_POST['dateofbirth'];

  $stmt = $connection->prepare("INSERT INTO users(email, password, firstname, lastname, dateofbirth) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param('sssss', $email, $userPassword, $firstname, $lastname, $dateofbirth);
  
  if ($stmt->execute()) {
    $message = "$firstname, your profile is successfully created.";
    $stmt->close();
  } else {
    $message = "Error: {$_stmt->error}";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <!-- Include Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Sign Up</h2>
    
    <form action="/gamermanagement/signup.php" method="post">
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-semibold mb-1">Email Address</label>
        <input type="email" id="email" name="email" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
        <input type="password" id="password" name="password" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="confirmPassword" class="block text-gray-700 font-semibold mb-1">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="firstName" class="block text-gray-700 font-semibold mb-1">First Name</label>
        <input type="text" id="firstname" name="firstname" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="lastName" class="block text-gray-700 font-semibold mb-1">Last Name</label>
        <input type="text" id="lastname" name="lastname" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="dateOfBirth" class="block text-gray-700 font-semibold mb-1">Date of Birth</label>
        <input type="date" id="dateofbirth" name="dateofbirth" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-1">reCAPTCHA</label>
        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
      </div>
      <div class="mb-4">
        <!-- Add your captcha implementation here -->
        <label for="captcha" class="block text-gray-700 font-semibold mb-1">Captcha</label>
        <input type="text" id="captcha" name="captcha" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
      </div>
      <div class="mt-6">
        <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Sign Up</button>
      </div>
    </form>
  </div>
</body>
</html>
