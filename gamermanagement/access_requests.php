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
    <title>Access Requests | Admin - Gamer Management</title>
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
      
    <h1 class="text-3xl font-semibold text-gray-800 ml-10 mt-4">Access Requests</h1> <br>

    <table class="min-w-full bg-white border border-gray-300">
      <thead>
        <tr>
          <th class="px-6 py-3 bg-gray-100 text-gray-700 font-semiboldtext-center">User ID</th>
          <th class="px-6 py-3 bg-gray-100 text-gray-700 font-semiboldtext-center">First Name</th>
          <th class="px-6 py-3 bg-gray-100 text-gray-700 font-semiboldtext-center">Last Name</th>
          <th class="px-6 py-3 bg-gray-100 text-gray-700 font-semiboldtext-center">Access Type</th>
          <th class="px-6 py-3 bg-gray-100 text-gray-700 font-semiboldtext-center">Department Name</th>

          <th class="px-6 py-3 bg-gray-100 text-gray-700 font-semiboldtext-center"></th>
        </tr>
      </thead>

      <tbody>
        <?php
          $servername = "localhost";
          $username = "root";
          $password = "";
          $database = "gamer_management";

          $connection = new mysqli($servername, $username, $password, $database);

          if($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
          }

          $query = "SELECT users.id, users.firstname, users.lastname, users.accesstype, department.name FROM users JOIN access_requests ON users.id = access_requests.user_id JOIN department ON users.department_id = department.id WHERE access_requests.request_type = 'Elevated Access User' AND access_requests.status = 'Pending'";
          $result = $connection->query($query);
         
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-center'>$row[id]</td>
                <td class='px-6 py-4 whitespace-nowrap text-center'>$row[firstname]</td>
                <td class='px-6 py-4 whitespace-nowrap text-center'>$row[lastname]</td>
                <td class='px-6 py-4 whitespace-nowrap text-center'>$row[accesstype]</td>
                <td class='px-6 py-4 whitespace-nowrap text-center'>$row[name]</td>
                <td class='px-6 py-4 whitespace-nowrap text-center'>
                  <div class='flex space-x-2'>
                    <a href='/gamermanagement/approve_request.php?id=$row[id]&user_id=$row[id]' class='px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-center'>Approve</a>
                    <a href='/gamermanagement/decline_request.php?id=$row[id]' class='px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-center'>Decline</a>
                  </div>
                </td>
              </tr>";
            }
          } else {
            echo "<tr>
              <td colspan='6' class='px-6 py-4 whitespace-nowrap text-center'>No pending access requests.</td>
            </tr>";
          }
        ?>    
      </tbody>
    </table>

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