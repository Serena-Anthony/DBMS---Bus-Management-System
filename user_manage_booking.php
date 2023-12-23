<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    $login_user = $_SESSION['user_session_token'];
    include("connect.php");
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Booking</title>
    <link rel="stylesheet" href="mb.css">
    <link rel="stylesheet" type="text/css" href="usermenu.css"  />
</head>
<body>
    <?php include("usermenu.php"); ?>
    <div class="container">
        <h1>Manage Booking</h1>

        <?php 
            if(isset($_REQUEST['did']))
            {
                $did = $_REQUEST['did'];
                mysqli_query($con, "update bookings set status='cancel', payment='reverse' where ticketid=$did");
                if(mysqli_affected_rows($con) > 0)
                {
                    setcookie("success", "Ticket cancelled successfully", time() + 3);
                    header("Location: user_manage_booking.php");
                }
                else
                {
                    setcookie("error", "Sorry! Unable to cancel ticket", time() + 3);
                    header("Location: user_manage_booking.php");
                }
            }
        ?>

        <?php 
            if(isset($_COOKIE['success']))
            {
                echo "<p>".$_COOKIE['success']."</p>";
            }
            if(isset($_COOKIE['error']))
            {
                echo "<p>".$_COOKIE['error']."</p>";
            }
        ?>

        <?php 
            $bookingResults = mysqli_query($con, "select * from bookings where userid=$login_user and status='booked' and payment='success' and travel_date >= '".date("Y-m-d")."'");
            if(mysqli_num_rows($bookingResults)>0)
            {
                ?>
                    <table id="booking-users">
                        <thead>
                            <tr>
                                <th>Ticket No</th>
                                <th>Service No</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Date of Journey</th>
                                <th>Start Time</th>
                                <th>Seat No</th>
                                <th>Name of the Passenger</th>
                                <th>Gander</th>
                                <th>Charge</th>
                                <th width="20%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                while($row = mysqli_fetch_assoc($bookingResults))
                                {
                                    $busno = $row['busnum'];
                                    $busInfo = mysqli_query($con, "select source,destination,start_time, charge from routes where busnum=$busno");
                                    $busrow = mysqli_fetch_assoc($busInfo);
                                    ?>
                                        <tr>
                                            <td><?php echo $row['ticketid']; ?></td>
                                            <td><?php echo $row['busnum']; ?></td>
                                            <td><?php echo $busrow['source']; ?></td>
                                            <td><?php echo $busrow['destination']; ?></td>
                                            <td><?php echo $row['travel_date']; ?></td>
                                            <td><?php echo $busrow['start_time']; ?></td>
                                            <td><?php echo $row['seatno']; ?></td>
                                            <td><?php echo $row['passenger_name']; ?></td>
                                            <td><?php echo $row['gender']; ?></td>
                                            <td>Rs.<?php echo $busrow['charge']; ?></td>
                                            <td>
                                            <a class="prep-post-btn" href="new_booking.php?ticketid=<?php echo $row['ticketid'] ?>&t=res">Re-Schedule</a>
                                            <a class="cancel-btn" onclick="deleteTicket(<?php echo $row['ticketid'] ?>)" href="javascript:void(0)">Cancel</a>
                                                
                                            </td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                <?php
            }
            else
            {
                ?>
                    <p>No Bookings Found</p>
                <?php
            }
        ?>
    </div>
    <script>
        function deleteTicket(tid)
        {
            if(confirm("Do you want to cancel ticket?"))
            {
                window.location="user_manage_booking.php?did="+tid;
            }
        }
    </script>
    <script src="mb.js"></script>
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