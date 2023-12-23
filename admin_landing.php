<?php 
ob_start();
session_start();
if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    include("connect.php");
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin_landing.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
    <div class="heading">
        <h1> Admin Dashboard </h1>
    </div>
    
    <div class="dashboard">
        <button onclick="navigateTo('admin_view_bookings.php')">View Bookings</button>
        <button onclick="navigateTo('admin_manage_routes.php')">Add Routes</button>
        <button onclick="navigateTo('admin_view_routes.php')">View  Routes</button>
        <button onclick="navigateTo('admin_view_history.php')">View History</button>
        <button onclick="navigateTo('admin_view_complaints.php')">View Complaints</button>
        <button onclick="navigateTo('logout.php')">Logout</button>
    </div>
</div>
    <script src="admin_landing.js"></script>

</body>
</html>
<?php 
mysqli_close($con);
}
else
{
    header("location: login.php");
}
ob_end_flush();
?>