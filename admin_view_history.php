<?php 
ob_start();
session_start();
if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    include("connect.php");
    ?>
<!-- Modify your HTML to include the submit button and link the JavaScript file -->
<!DOCTYPE html>
<html>
<head>
    <title>View History</title>
    <link rel="stylesheet" type="text/css" href="admin_view_history.css" />
    <link rel="stylesheet" type="text/css" href="usermenu.css" />
    <!-- Link to your external CSS file -->
</head>
<body>
    <?php 
        include("adminmenu.php");
    ?>
    <div class="form-container"  style="margin-bottom:40px;">
        <h1>View History</h1>
        <form id="historyForm" method="GET" action="">
            <label for="userId">Select User ID:</label>
            <input list="users" name="userid" id="userid">
            <datalist id="users">
            <?php 
                    $userResult = mysqli_query($con, "select * from users where role='user'");
                    if(mysqli_num_rows($userResult) > 0)
                    {
                        while($row = mysqli_fetch_assoc($userResult))
                        {
                            ?>
                                <option  value="<?php echo $row['id'];?>" /> 
                            <?php
                        }
                    }
                ?>
            </datalist>
            
                
            
            <!-- <input type="text" id="userId" name="userId" /> -->
            <input type="submit" class="submit-button" value="Submit" onclick="submitForm()" />
        </form>
    </div>

    <div class="user-history">
        <?php 
            if(isset($_REQUEST['userid']))
            {
                $userid = $_REQUEST['userid'];
               $bookingInfo =  mysqli_query($con, "select 
                users.username, users.mobile,
                bookings.ticketid, bookings.travel_date, bookings.seatno, bookings.status, 
                routes.source,routes.charge, routes.destination, routes.busnum from users INNER JOIN bookings ON users.id 
                 = bookings.userid INNER JOIN routes ON bookings.busnum = routes.busnum where bookings.userid = $userid");
                if(mysqli_num_rows($bookingInfo) > 0)
                {
                    $i = 1;
                    ?>
                        <table id="customers">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Passenger Name</th>
                                    <th>Mobile No</th>
                                    <th>Ticket</th>
                                    <th>Travel date</th>
                                    <th>Service Number</th>
                                    <th>Seat Number</th>
                                    <th>Charge</th>
                                    <th>Source</th>
                                    <th>Destination</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while($brow = mysqli_fetch_assoc($bookingInfo))
                                {
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $brow['username']; ?></td>
                                            <td><?php echo $brow['mobile']; ?></td>
                                            <td><?php echo $brow['ticketid']; ?></td>
                                            <td><?php echo $brow['travel_date']; ?></td>
                                            <td><?php echo $brow['busnum']; ?></td>
                                            <td><?php echo $brow['seatno']; ?></td>
                                            <td>Rs.<?php echo $brow['charge']; ?>/-</td>
                                            <td><?php echo $brow['source']; ?></td>
                                            <td><?php echo $brow['destination']; ?></td>
                                        </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                }
                else
                {
                    ?>
                        <p>No Bookings found</p>
                    <?php
                }
            }
        ?>
    </div>

    

    <script src="admin_view_history.js"></script>
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
