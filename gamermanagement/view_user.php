<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {  
    header("location:login.php");  
    die();  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Details</title>
  <!-- Include Tailwind CSS -->
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
            <a href="/gamermanagement/welcomeadmin.php" class="text-white hover:text-gray-200">Welcome</a>
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

    <div class="bg-gray-100 min-h-screen p-8 mb-9">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
            <!-- User Details -->
            <h2 class="text-2xl font-semibold mb-4">User Details</h2>
            <div class="space-y-2">
                <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "gamer_management";

                    $connection = new mysqli($servername, $username, $password, $database);

                    if($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        $id = $_GET['id'];
                        $sql = "SELECT users.*, department.name AS department_name FROM users
                        LEFT JOIN department ON users.department_id = department.id
                        WHERE users.id=$id";
                        
                        $result = $connection->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo"
                                    <p><span class='font-semibold'>Email:</span> $row[email]</p>
                                    <p><span class='font-semibold'>First Name:</span> $row[firstname]</p>
                                    <p><span class='font-semibold'>Last Name:</span> $row[lastname]</p>
                                    <p><span class='font-semibold'>Date of Birth:</span> $row[dateofbirth]</p>
                                    <p><span class='font-semibold'>Access Type:</span> $row[accesstype]</p>
                                    <p><span class='font-semibold'>Phone Number:</span> $row[phonenumber]</p>
                                    <p><span class='font-semibold'>Department:</span> $row[department_name]</p>
                                    <p><span class='font-semibold'>Address:</span> $row[address]</p>
                                ";
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>

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