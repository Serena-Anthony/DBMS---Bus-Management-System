<?php 
    $filename = '';

    $arr = explode("/", $_SERVER['PHP_SELF']);

    $filename = end($arr);
?>
<div class="navbar">
        <ul class="usermenu">
            <li class="<?php if($filename === "admin_landing.php") echo "active" ?>">
                <a href="admin_landing.php">Dashboard</a>
            </li>
            <li class="<?php if($filename === "admin_view_bookings.php") echo "active" ?>">
                <a href="admin_view_bookings.php">View Bookings</a>
            </li>
            <li class="<?php if($filename === "admin_manage_routes.php") echo "active" ?>">
                <a href="admin_manage_routes.php">Add Routes</a>
            </li>
            <li class="<?php if($filename === "admin_view_routes.php") echo "active" ?>">
                <a href="admin_view_routes.php">Manage Routes</a>
            </li>
            <li class="<?php if($filename === "admin_view_history.php") echo "active" ?>">
                <a href="admin_view_history.php">View History</a>
            </li>
            <li class="<?php if($filename === "admin_view_complaints.php") echo "active" ?>">
                <a href="admin_view_complaints.php">View Complaints</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </div>