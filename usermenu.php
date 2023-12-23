<?php 
    $filename = '';

    $arr = explode("/", $_SERVER['PHP_SELF']);

    $filename = end($arr);
?>
<div class="navbar">
        <ul class="usermenu">
            <li class="<?php if($filename === "user_dashboard.php") echo "active" ?>">
                <a href="user_dashboard.php">Dashboard</a>
            </li>
            <li class="<?php if($filename === "new_booking.php") echo "active" ?>">
                <a href="new_booking.php">Booking</a>
            </li>
            <li class="<?php if($filename === "user_manage_booking.php") echo "active" ?>">
                <a href="user_manage_booking.php">Manage Booking</a>
            </li>
            <li class="<?php if($filename === "user_vh.php") echo "active" ?>">
                <a href="user_vh.php">History</a>
            </li>
            <li class="<?php if($filename === "complaint.php") echo "active" ?>">
                <a href="complaint.php">Register Complaint</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </div>