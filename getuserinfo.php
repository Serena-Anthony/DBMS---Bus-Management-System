<?php 
    if(isset($_REQUEST['tid']))
    {
        include("connect.php");
        $tid = $_REQUEST['tid'];
        $result = mysqli_query($con, "select passenger_name, age, gender from bookings where ticketid='$tid'");
        $obj = mysqli_fetch_assoc($result);
    }
    else
    {
        $obj = ["passenger_name" => "", "gender" => "", "age" =>""];
    }
    echo json_encode($obj);
?>