<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    include("connect.php");
    if(isset($_REQUEST['ticketid']) && isset($_REQUEST['t']))
    {
        $ticketid = $_REQUEST['ticketid'];
        $ticketinfo = mysqli_query($con, "select routes.source, routes.destination, bookings.busnum from routes INNER JOIN bookings ON routes.busnum = bookings.busnum where bookings.ticketid=$ticketid");
        $ticketRow = mysqli_fetch_assoc($ticketinfo);
        
    }
    ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="new_booking_styles.css">
    <link rel="stylesheet" type="text/css" href="usermenu.css"  />
</head>
<body>
    <?php include("usermenu.php"); ?>
    <div class="container">
        <?php 
            if(isset($_REQUEST['ticketid']) && isset($_REQUEST['t']))
            {
                ?>
                    <h1>Re Schedule Booking</h1>
                <?php
            }
            else
            {
                ?>
                    <h1>Bus Ticket Booking</h1>
                <?php
            }
        ?>
        
        <form action="new_booking_2.php">
            <?php 
                if(isset($_REQUEST['ticketid']) && isset($_REQUEST['t']))
                {
                    ?>
                        <input type="hidden" name='ticketid' value="<?php echo $_REQUEST['ticketid']; ?>" />
                        <input type="hidden" name='t' value="<?php echo $_REQUEST['t']; ?>" />
                        <input type="hidden" name='busnum' value="<?php echo $ticketRow['busnum']; ?>" />
                    <?php
                }
            ?>
            <input type="hidden" value="" />
            <label for="source">Source:</label>
            <select id="source" name="source" required>
                <?php 
                    if(isset($_REQUEST['ticketid']) && isset($_REQUEST['t']))
                    {
                        ?>
                            <option value="<?php echo $ticketRow['source'] ?>"><?php echo $ticketRow['source'] ?></option>
                        <?php
                    }
                    else
                    {
                ?>
                <option value="" disabled selected>Select a district</option>
                <option value="Trivandrum">Trivandrum</option>
                <option value="Kollam">Kollam</option>
                <option value="Pathanamthitta">Pathanamthitta</option>
                <option value="Alappuzha">Alappuzha</option>
                <option value="Kottayam">Kottayam</option>
                <option value="Idukki">Idukki</option>
                <option value="Ernakulam">Ernakulam</option>
                <option value="Thrissur">Thrissur</option>
                <option value="Palakkad">Palakkad</option>
                <option value="Malappuram">Malappuram</option>
                <option value="Kozhikode">Kozhikode</option>
                <option value="Wayanad">Wayanad</option>
                <option value="Kannur">Kannur</option>
                <option value="Kasaragod">Kasaragod</option>
                <?php 
                    }
                ?>
            </select>

            <label for="destination">Destination:</label>
            <select id="destination" name="destination" required>
                <?php 
                 if(isset($_REQUEST['ticketid']) && isset($_REQUEST['t']))
                 {
                     ?>
                         <option value="<?php echo $ticketRow['destination'] ?>"><?php echo $ticketRow['destination'] ?></option>
                     <?php
                 }
                 else
                 {
             ?>
                <option value="" disabled selected>Select a district</option>
                <option value="Trivandrum">Trivandrum</option>
                <option value="Kollam">Kollam</option>
                <option value="Pathanamthitta">Pathanamthitta</option>
                <option value="Alappuzha">Alappuzha</option>
                <option value="Kottayam">Kottayam</option>
                <option value="Idukki">Idukki</option>
                <option value="Ernakulam">Ernakulam</option>
                <option value="Thrissur">Thrissur</option>
                <option value="Palakkad">Palakkad</option>
                <option value="Malappuram">Malappuram</option>
                <option value="Kozhikode">Kozhikode</option>
                <option value="Wayanad">Wayanad</option>
                <option value="Kannur">Kannur</option>
                <option value="Kasaragod">Kasaragod</option>
                <!-- Add other district options here -->
                <?php 
                 }
                ?>
            </select>

            <label for="travel-date">Date of Travel:</label>
            <input type="date" id="travel-date" required min="<?php echo date("Y-m-d") ?>" max="<?php echo date("Y-m-d", strtotime("1 month")); ?>" name="travel_date">
            
            <button type="submit" value="Book Ticket"> Submit </button>
        </form>
    </div>
    <script src="new_booking_script.js"></script>
</body>
</html>
<?php 
}
else
{
    header("Location: login.php");
}
ob_end_flush()
?>