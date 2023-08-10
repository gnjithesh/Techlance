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

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $id = $_GET["id"];
      $sql = "SELECT * FROM users WHERE id=$id";
      $result = $connection->query($sql);
      $row = $result->fetch_assoc();

      $email = $row["email"];
      $userPassword = $row["password"]; // Changed variable name to avoid conflict
      $firstname = $row["firstname"];
      $lastname = $row["lastname"];
      $dateofbirth = $row["dateofbirth"];
      $accesstype = $row["accesstype"];
      $phonenumber = $row["phonenumber"];
      $department_id = $row["department_id"]; // Corrected variable name
      $address = $row["address"];

    } else {
      $id = $_POST["id"];
      $email = $_POST["email"];
      $userPassword = $_POST["password"]; // Changed variable name to avoid conflict
      $firstname = $_POST["firstname"];
      $lastname = $_POST["lastname"];
      $dateofbirth = date('Y-m-d', strtotime($_POST["dateofbirth"]));
      $accesstype = $_POST["accesstype"];
      $phonenumber = $_POST["phonenumber"];
      $department_id = $_POST["department_id"]; // Corrected variable name
      $address = $_POST["address"];

      $stmt = $connection->prepare("UPDATE users SET email = ?, Password = ?, firstname = ?, lastname = ?, dateofbirth = ?," . 
      "accesstype = ?, phonenumber = ?, department_id = ?, address = ? WHERE id = ?");
      $stmt->bind_param("sssssssssi", $email, $userPassword, $firstname, $lastname, $dateofbirth, $accesstype, $phonenumber, $department_id, $address, $id);
      
      if ($stmt->execute()) {
        echo "User information updated successfully.";
    } else {
        echo "Error updating user information: " . $stmt->error;
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
    <title>Edit User | Admin - Gamer Management</title>
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

    <h1 class="text-3xl font-semibold text-gray-800 ml-10 mt-4">Edit User</h1>

    <form class="max-w-md mx-auto" method="post">

      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <div class="mb-4">
        <label for="email" class="block mb-2 text-lg font-medium text-gray-700">Email Address</label>
        <input id="email" type="email" name="email" value="<?php echo $email ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email address" required>
      </div>

      <div class="mb-4">
        <label for="password" class="block mb-2 text-lg font-medium text-gray-700">Password</label>
        <input id="password" type="password" name="password" value="<?php echo $userPassword ?>"class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password" required>
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label for="firstName" class="block mb-2 text-lg font-medium text-gray-700">First Name</label>
          <input id="firstName" type="text" name="firstname" value="<?php echo $firstname ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your first name" required>
        </div>

        <div>
          <label for="lastName" class="block mb-2 text-lg font-medium text-gray-700">Last Name</label>
          <input id="lastName" type="text" name="lastname" value="<?php echo $lastname ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your last name" required>
        </div>
      </div>

      <div class="mb-4">
        <label for="dob" class="block mb-2 text-lg font-medium text-gray-700">Date of Birth</label>
        <input id="dob" type="date" name="dateofbirth" value="<?php echo $dateofbirth ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div class="mb-4">
        <label for="accessType" class="block mb-2 text-lg font-medium text-gray-700">Access Type</label>
        <select id="accessType" name="accesstype" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          <option value="" disabled>Select access type</option>
          <option value="admin" <?php if ($accesstype === 'Admin') echo 'selected'; ?>>Administrator</option>
          <option value="elevated" <?php if ($accesstype === 'elevated') echo 'selected'; ?>>Elevated Access User</option>
          <option value="user" <?php if ($accesstype === 'User') echo 'selected'; ?>>User</option>
      </select>
      </div>

      <div class="mb-4">
        <label for="phoneNumber" class="block mb-2 text-lg font-medium text-gray-700">Phone Number</label>
        <input id="phoneNumber" type="tel" name="phonenumber" value="<?php echo $phonenumber ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your phone number">
      </div>

      <div class="mb-4">
        <label for="department" class="block mb-2 text-lg font-medium text-gray-700">Department</label>
        <select id="department" name="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          <option value="" disabled>Select Department</option>
          <option value="1" <?php if ($department_id == 1) echo 'selected'; ?>>Real-Time Strategy</option>
          <option value="2" <?php if ($department_id == 2) echo 'selected'; ?>>Technology</option>
          <option value="3" <?php if ($department_id == 3) echo 'selected'; ?>>Board Game</option>
          <option value="4" <?php if ($department_id == 4) echo 'selected'; ?>>Card Game</option>
          <option value="5" <?php if ($department_id == 5) echo 'selected'; ?>>Arcade Game</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="address" class="block mb-2 text-lg font-medium text-gray-700">Address</label>
        <input id="address" name="address" value="<?php echo $address ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your address" rows="3"></input>
      </div>

      <div class="mt-6">
        <button type="submit" name="save" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Edit</button>
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



