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
    <title>Users | Elevated User - Gamer Management</title>
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
            <a href="/gamermanagement/users_list.php" class="text-white hover:text-gray-200">Users</a>
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
      
    <h1 class="text-3xl font-semibold text-gray-800 ml-10 mt-4">Users</h1> <br>

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

          //$query = "SELECT * FROM users";
          $adminId = $_SESSION['USER_ID'];
          $query = "SELECT u.id, u.firstname, u.lastname, IFNULL(u.accesstype, '') AS accesstype, IFNULL(d.name, '') AS department_name
          FROM users u LEFT JOIN department d ON u.department_id = d.id
          WHERE u.id != $adminId";

          $result = $connection->query($query);

          if(!$result) {
            die("Invalid query:" . $connection->error);
          }

          while($row = $result->fetch_assoc()) {
            echo "<tr>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[id]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[firstname]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[lastname]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[accesstype]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[department_name]</td>
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