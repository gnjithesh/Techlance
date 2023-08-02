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

if($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle the search functionality
$searchQuery = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // You may need to sanitize the user input to prevent SQL injection
    $search = $connection->real_escape_string($search);
    // Modify the query to include the search condition
    $searchQuery = " AND (u.firstname LIKE '%$search%' OR u.lastname LIKE '%$search%')";
}

// Retrieve the department filter selection
$selectedDepartment = "";
if (isset($_GET['department'])) {
    $selectedDepartment = $_GET['department'];
    // sanitize the user input to prevent SQL injection
    $selectedDepartment = $connection->real_escape_string($selectedDepartment);
    // query to include the department filter
    if (!empty($selectedDepartment)) {
        $searchQuery .= " AND d.id = '$selectedDepartment'";
    }
}

// final SQL query
$adminId = $_SESSION['USER_ID'];
$query = "SELECT u.id, u.firstname, u.lastname, IFNULL(u.accesstype, '') AS accesstype, IFNULL(d.name, '') AS department_name
FROM users u LEFT JOIN department d ON u.department_id = d.id
WHERE u.id != $adminId $searchQuery";

$result = $connection->query($query);

if (!$result) {
    die("Invalid query:" . $connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | Admin - Gamer Management</title>
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
      
    <h1 class="text-3xl font-semibold text-gray-800 ml-10 mt-4">Users</h1> <br>

    <a href="/gamermanagement/adduser.php" role="button" class="m-9 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">Add User</a> <br><br>

    <div class="container mx-auto px-4 py-6">
        <!-- Search container -->
        <div class="flex items-center justify-between mb-4">
            <!-- Search input -->
            <form action="" method="get" class="relative flex items-center">
                <input type="text" name="search" class="ml-5 w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="ml-4 top-0 right-0 h-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Search
                </button>
            </form>
            
            <!-- Drop-down filter -->
            <form action="" method="get" class="relative mr-20">
                <select name="department" class="px-4 py-2 border border-gray-300 rounded-md">
                    <option value="">All Departments</option>
                    <!-- department names dynamically from database -->
                    <option value="1" <?php echo ($selectedDepartment === '1') ? 'selected' : ''; ?>>Real-Time Strategy</option>
                    <option value="2" <?php echo ($selectedDepartment === '2') ? 'selected' : ''; ?>>Technology</option>
                    <option value="3" <?php echo ($selectedDepartment === '3') ? 'selected' : ''; ?>>Board Game</option>
                    <option value="4" <?php echo ($selectedDepartment === '4') ? 'selected' : ''; ?>>Card Game</option>
                    <option value="5" <?php echo ($selectedDepartment === '5') ? 'selected' : ''; ?>>Arcade Game</option>
                    
                </select>
                <svg class="absolute top-2 right-2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                </svg>
                <button type="submit" class=" top-0 right-10 h-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Apply Filter
                </button>
            </form>
        </div>
      </div>

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
          while($row = $result->fetch_assoc()) {
            echo "<tr>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[id]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[firstname]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[lastname]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[accesstype]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>$row[department_name]</td>
              <td class='px-6 py-4 whitespace-nowrap text-center'>
                <div class='flex space-x-2'>
                  <a href='/gamermanagement/view_user.php?id=$row[id]' class='px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-center'>View</a>
                  <a href='/gamermanagement/edituser.php?id=$row[id]' class='px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-center'>Edit</a>
                  <a href='/gamermanagement/delete.php?id=$row[id]' class='px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-center'>Delete</a>
                </div>
              </td>
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

