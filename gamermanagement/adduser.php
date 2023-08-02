<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {  
    header("location:login.php");  
    die();  
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "gamer_management";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $userPassword = $_POST["password"]; // Changed variable name to avoid conflict
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dateofbirth = date('Y-m-d', strtotime($_POST["dateofbirth"]));
    $accesstype = $_POST["accesstype"];
    $phonenumber = $_POST["phonenumber"];
    $department_id = $_POST["department_id"]; // Corrected variable name
    $address = $_POST["address"];
    
    // Using prepared statement to prevent SQL injection
    $stmt = $connection->prepare("INSERT INTO users (email, password, firstname, lastname, dateofbirth, accesstype, phonenumber, address, department_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $email, $userPassword, $firstname, $lastname, $dateofbirth, $accesstype, $phonenumber, $address, $department_id);
    
    if ($stmt->execute()) {
        $message = "User added successfully!!!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User | Admin - Gamer Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <nav class="bg-blue-500 py-4">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <span class="text-white text-xl font-semibold">Gamer Management</span>
          </div>
          <div class="flex items-center space-x-4">
            <a href="/gamermanagement/admin_dashboard.php" class="text-white hover:text-gray-200">Welcome</a>
            <a href="/gamermanagement/admin_profile.php" class="text-white hover:text-gray-200">Profile</a>
            <a href="/gamermanagement/users.php" class="text-white hover:text-gray-200">Users</a>
            <a href="/gamermanagement/access_requests.php" class="text-white hover:text-gray-200">Access Requests</a>
            <!-- Dropdown Menu -->
            <div class="relative">
              <button class="text-white hover:text-gray-200 focus:outline-none">
                <?php echo $_SESSION['USER_FIRSTNAME']; ?> ▼ <!-- Display the user's firstname -->
              </button>
              <ul class="absolute right-0 mt-2 w-32 bg-white border rounded-lg shadow-lg hidden">
                <li><a href="/gamermanagement/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-red-500 hover:text-white">Logout</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <h1 class="text-3xl font-semibold text-gray-800 ml-10 mt-4">Add User</h1>

    <form class="max-w-md mx-auto" action="/gamermanagement/adduser.php" method="post">
      <div class="mb-4">
        <label for="email" class="block mb-2 text-lg font-medium text-gray-700">Email Address</label>
        <input id="email" type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email address" required>
      </div>

      <div class="mb-4">
        <label for="password" class="block mb-2 text-lg font-medium text-gray-700">Password</label>
        <input id="password" type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password" required>
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label for="firstName" class="block mb-2 text-lg font-medium text-gray-700">First Name</label>
          <input id="firstName" type="text" name="firstname" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your first name" required>
        </div>

        <div>
          <label for="lastName" class="block mb-2 text-lg font-medium text-gray-700">Last Name</label>
          <input id="lastName" type="text" name="lastname" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your last name" required>
        </div>
      </div>

      <div class="mb-4">
        <label for="dob" class="block mb-2 text-lg font-medium text-gray-700">Date of Birth</label>
        <input id="dob" type="date" name="dateofbirth" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
      </div>

      <div class="mb-4">
        <label for="accessType" class="block mb-2 text-lg font-medium text-gray-700">Access Type</label>
        <select id="accessType" name="accesstype" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          <option value="" disabled selected>Select access type</option>
          <option value="admin">Administrator</option>
          <option value="elevated">Elevated Access User</option>
          <option value="user">User</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="phoneNumber" class="block mb-2 text-lg font-medium text-gray-700">Phone Number</label>
        <input id="phoneNumber" type="tel" name="phonenumber" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your phone number">
      </div>

      <div class="mb-4">
        <label for="department" class="block mb-2 text-lg font-medium text-gray-700">Department</label>
        <select id="department" name="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          <option value="" disabled selected>Select Department</option>
          <option value="1">Real-Time Strategy</option>
          <option value="2">Technology</option>
          <option value="3">Board Game</option>
          <option value="4">Card Game</option>
          <option value="5">Arcade Game</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="address" class="block mb-2 text-lg font-medium text-gray-700">Address</label>
        <textarea id="address" name="address" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your address" rows="3"></textarea>
      </div>

      <div class="mt-6">
        <button type="submit" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Save</button>
      </div>
    </form>

    <!-- Include JavaScript to toggle the dropdown menu -->
    <script>
      const dropdownButton = document.querySelector('.relative button');
      const dropdownMenu = document.querySelector('.relative ul');

      dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
      });
    </script>
  </body>
</html>