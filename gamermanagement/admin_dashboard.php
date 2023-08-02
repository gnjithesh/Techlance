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
    <title>Admin Dashboard</title>
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
    </nav>

    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md text-center">
            <h2 class="text-2xl font-semibold mb-4">Welcome, <?php echo $_SESSION['USER_FIRSTNAME'] . ' ' . $_SESSION['USER_LASTNAME']; ?></h2>   
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
</html>

