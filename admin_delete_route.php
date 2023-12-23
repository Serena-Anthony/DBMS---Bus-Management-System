<?php 
ob_start();
session_start();
if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    $bid = $_REQUEST['bid'];
    include("connect.php");
    if(!(isset($_REQUEST['bid']) && !empty($_REQUEST['bid'])))
    {
        header("Location: admin_view_routes.php");
    }
    
    $date = date("Y-m-d");
    $isAllotted = mysqli_query($con, "select count(*) allotted from bookings where busnum=$bid and travel_date >= '$date'");
    $row = mysqli_fetch_assoc($isAllotted);
    if($row["allotted"] === 0)
    {
        $routeInfo = mysqli_query($con, "delete from routes where busnum=$bid");

        if(mysqli_affected_rows($con) === 1)
        {
            setcookie("success", "Route deleted successfully", time() + 3);
            header("location: admin_view_routes.php"); 
        }
    }
    else
    {
            setcookie("error", "You can not delete this route. Already tickets booked.", time() + 3);
            header("location: admin_view_routes.php");
    }
   
}
else
{
    header("location: login.php");
}
    ?>