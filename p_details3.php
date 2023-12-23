<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    include("connect.php");
    $loggedin_user = $_SESSION['user_session_token'];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="passenger-details.css">
    <link rel="stylesheet" type="text/css" href="usermenu.css"  />
</head>
<body>  
    <?php include("usermenu.php"); ?>
    <div class="container">
        <h1>Bus Seat Reservation</h1>
        <?php 
             $source = $destination = $travel_date = $serviceno = '';

             if(isset($_REQUEST['serviceno']))
             {
                 $serviceno = $_REQUEST['serviceno'];
             }
             else
             {
                
             }

             $busInfo = mysqli_query($con, "select * from routes where busnum=$serviceno");
             
             if(isset($_REQUEST['source']))
             {
                 $source = $_REQUEST['source'];
             }

             if(isset($_REQUEST['destination']))
             {
                 $destination = $_REQUEST['destination'];
             }

             if(isset($_REQUEST['date']))
             {
                 $travel_date = $_REQUEST['date'];
             }
 
             if(mysqli_num_rows($busInfo) > 0)
             {
                $busrow = mysqli_fetch_assoc($busInfo);
                ?>
                <table border='1'>
                    <thead>
                        <tr>
                            <th>Service No</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Date of Journey</th>
                            <th>Start Time</th>
                            <th>Fare</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $busrow['busnum']; ?></td>
                            <td><?php echo $source ?></td>
                            <td><?php echo $destination ?></td>
                            <td><?php echo $travel_date ?></td>
                            <td><?php echo $busrow['start_time'] ?></td>
                            <td>Rs. <?php echo $busrow['charge'] ?>/-</td>
                            <input type="hidden" id="unit_fare" value="<?php echo $busrow['charge']; ?>" />
                            <input type="hidden" id="loggedin_user" value="<?php echo $loggedin_user; ?>" />
                            <?php 
                                if(isset($_REQUEST['ticketid']))
                                {

                                    $tktid = $_REQUEST['ticketid'];
                                    $ticketUserInfo = mysqli_query($con, "select passenger_name, gender, age from bookings where ticketid='$tktid'");
                                    $tktrow = mysqli_fetch_assoc($ticketUserInfo);
                                    ?>
                                        <input type="hidden" id="ticketid" value="<?php echo $_REQUEST['ticketid']; ?>" />
                                        <input type="hidden" id="passenger_name" value="<?php echo $tktrow['passenger_name']; ?>" />
                                        <input type="hidden" id="gender" value="<?php echo $tktrow['gender']; ?>" />
                                        <input type="hidden" id="age" value="<?php echo $tktrow['age']; ?>" />
                                    <?php
                                }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <?php
             }


             /*$displaySeats= mysqli_query($con, "select routes.total_seats, routes.charge, bookings.seatno from routes INNER JOIN bookings ON routes.busnum = bookings.busnum where routes.busnum=$serviceno and bookings.travel_date='$travel_date'");*/

             $displaySeats= mysqli_query($con, "select total_seats, charge from routes where busnum=$serviceno");

             $bookedSeats = [];
             $seatsInfo = mysqli_query($con, "select seatno from bookings where busnum=$serviceno and travel_date='$travel_date' and status='booked' and payment='success'");
             if(mysqli_num_rows($seatsInfo) > 0)
             {
                while($bsrow = mysqli_fetch_assoc($seatsInfo))
                {
                    $bookedSeats[] = $bsrow['seatno'];
                }
             }

             

             if(mysqli_num_rows($displaySeats))
             {

                $row = mysqli_fetch_assoc($displaySeats);
               
                ?>
                <!-- <p><strong>Total Seats Available:</strong> <span id="total-seats"><?php echo $row['total_seats'] ?></span></p> -->
                <div id="bus-layout">
                    <?php
                        for($i = 1; $i <= $row['total_seats']; $i++)
                        {
                            if(in_array($i, $bookedSeats))
                            {
                                ?>
                                    <div class="blocked"><?php echo $i; ?></div>
                                <?php
                            }
                            else
                            {
                                ?>
                                 <div data-selected='no' data-seat="<?php echo $i; ?>" id="seat_<?php echo $i; ?>" class="seat"><?php echo $i; ?></div>
                                <?php
                            }
                           
                        }
                    ?>
                </div>
                <div class="passengers"></div>
                <div style="padding: 10px 0px">
                    <!-- <p class="total-fare">Total Fare: 0</p> -->
                    <button class="booknow">Book Now</button>
                </div>
                <?php 
             }
             else
             {
                ?>
                    <p>Sorry! Something went Wrong</p>
                <?php
             }
        ?>
        
        <!-- </div> -->
        <!-- <form id="passenger-form">
    
            <div id="passenger-inputs"></div>
            <p><strong>Total Amount to be Paid:</strong> <span id="total-amount">Rs. 0.00</span></p>
            <input type="submit" value="Proceed to Payment">
        </form> -->
    </div>
    <script src="passenger-details.js"></script>
</body>
</html>
<?php 
    mysqli_close($con);
}
else
{
    header("Location: login.php");
}
ob_end_flush()
?>