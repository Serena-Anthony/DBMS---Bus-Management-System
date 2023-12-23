<?php 
include("connect.php");

    $bookingData = json_decode(file_get_contents("php://input"));
    $transactionID = rand(10000000, 99999999)+time();
    $status = null;
    if(count($bookingData) > 0)
    {
        for($i = 0; $i < count($bookingData); $i++)
        {
            $ticketid = $bookingData[$i]->ticketid;
            $userid = $bookingData[$i]->userid;
            $busnum = $bookingData[$i]->busnum;
            $travel_date = $bookingData[$i]->travel_date;
            $seatno = $bookingData[$i]->seatno;
            $passenger_name = $bookingData[$i]->passenger_name;
            $gender = $bookingData[$i]->gender;
            $age = $bookingData[$i]->age;
            $action = $bookingData[$i]->action;
            if($action=="edit")
            {
                mysqli_query($con, "UPDATE bookings SET passenger_name='$passenger_name', age='$age', gender='$gender', travel_date='$travel_date', seatno='$seatno' where ticketid='$ticketid'");
                if(mysqli_affected_rows($con))
                {
                    $status = true;
                }
            }
            else
            {
                mysqli_query($con, "INSERT INTO bookings(userid, ticketid,busnum,travel_date,seatno,passenger_name, gender, age, transactionid) VALUES('$userid','$ticketid','$busnum', '$travel_date', '$seatno', '$passenger_name', '$gender', '$age', '$transactionID')");

                if(mysqli_affected_rows($con))
                {
                    $status = true;
                }
            }
           

        }

        if($status)
        {
            if($action === "edit")
            {
                echo json_encode(["status" => "success", "action" => "edit"]);
            }
            else
            {
                echo json_encode(["status" => "success","tid" => $transactionID, "action" => "add"]);
            }
            
        }
        else
        {
            echo json_encode(["status" => "error"]);
        }
    }
    else{
        echo json_encode(["status" => "error"]);
    }

mysqli_close($con);
?>