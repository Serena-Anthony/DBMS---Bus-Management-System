<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    include("connect.php");
    $uid = $_SESSION['user_session_token'];
    $result = mysqli_query($con, "select username, mobile from users where id=$uid");
    $row = mysqli_fetch_assoc($result);
    ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome <?php echo ucwords($row['username']); ?> Admin</title>
            <link rel="stylesheet" href="user_dashboard.css">
        </head>
        <body>
            <div class="container">
                <h1><?php echo ucwords($row['username']); ?> Dashboard </h1>
                <div class="contain">
                <button onclick="redirectTo('new_booking.php')">New Booking</button>
                <button onclick="redirectTo('user_manage_booking.php')">Manage Booking</button>
                <button onclick="redirectTo('user_vh.php')">View History</button>
                <button onclick="redirectTo('complaint.php')">Complaint Registration</button>
                <button onclick="redirectTo('logout.php')">Logout</button>
                </div>
            </div>
            <script src="user_dashboard.js"></script>
        </body>
        </html>
    <?php
    mysqli_close($con);
}
else
{
    header("location: login.php");
}
?>

<?php ob_end_flush(); ?>
