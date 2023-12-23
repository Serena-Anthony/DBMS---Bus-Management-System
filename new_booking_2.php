<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    include("connect.php");

    if(isset($_REQUEST['ticketid']))
    {
        $ticketid = $_REQUEST['ticketid'];
        $result = mysqli_query($con, "select travel_date from bookings where ticketid='$ticketid'");
        $crow = mysqli_fetch_assoc($result);
        if($crow['travel_date'] === $_REQUEST['travel_date'])
        {
            ?>
                <script>
                   alert("Both Reschedule date and booking dates are same, please choose different date");
                   window.location = "<?php echo $_SERVER['HTTP_REFERER']?>";
                </script>
            <?php
        }
    }

    ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="new_booking_2_styles.css">
    <link rel="stylesheet" type="text/css" href="usermenu.css"  />
</head>
<body>
    <?php include("usermenu.php"); ?>
    <div class="container">
        
        
        <?php 
            $source = $destination = $travel_date = '';
            if(isset($_REQUEST['source']))
            {
                $source = $_REQUEST['source'];
            }
            if(isset($_REQUEST['destination']))
            {
                $destination = $_REQUEST['destination'];
            }
            if(isset($_REQUEST['travel_date']))
            {
                $travel_date = $_REQUEST['travel_date'];
            }
        ?>
            <?php 
                
                if(isset($_REQUEST['ticketid']) && $_REQUEST['t'])
                {
                    ?>
                        <h1>Available Bus:  <?php echo $travel_date; ?></h1>
                    <?php
                }
                else
                {
                    ?>
                        <h1>Available Buses:  <?php echo $travel_date; ?></h1>
                    <?php
                }
            ?>
            
        <?php
        if(isset($_REQUEST['ticketid']) && $_REQUEST['t'])
        {
            $busnum = $_REQUEST['busnum'];
            $availableServices = mysqli_query($con, "select * from routes where source='$source' and destination='$destination' and busnum=$busnum");
        }
        else
        {   
            $availableServices = mysqli_query($con, "select * from routes where source='$source' and destination='$destination'");
        }
            
            if(mysqli_num_rows($availableServices) > 0)
            {
                while($row = mysqli_fetch_assoc($availableServices))
                {
                                ?>
                    <div class="bus-card">
                        <h2>Service No: <?php echo $row['busnum']; ?></h2>
                        <p><strong>Source:</strong><?php echo $source ?></p>
                        <p><strong>Destination:</strong><?php echo $destination ?></p>
                        <p><strong>Time of Travel:</strong><?php echo $row['start_time'] ?> - <?php echo $row['end_time']; ?></p>
                        <!-- <p><strong>Seats Available:</strong><?php echo $row['total_seats']; ?></p> -->
                        <p><strong>Price:</strong> Rs. <?php echo $row['charge'];?></p>
                        <?php 
                        if(isset($_REQUEST['ticketid']) && $_REQUEST['t'])
                        {
                            $ticketid = $_REQUEST['ticketid'];
                            ?>
                                <p style="margin-top: 20px;"><a href="p_details3.php?serviceno=<?php echo $row['busnum'] ?>&date=<?php echo $travel_date ?>&source=<?php echo $source; ?>&destination=<?php echo $destination?>&ticketid=<?php echo $ticketid; ?>" class="book-button">Book</a></p>
                            <?php
                        }
                        else
                        {
                            ?>
                                <p style="margin-top: 20px;"><a href="p_details3.php?serviceno=<?php echo $row['busnum'] ?>&date=<?php echo $travel_date ?>&source=<?php echo $source; ?>&destination=<?php echo $destination?>" class="book-button">Book</a></p>
                            <?php
                        }
                        ?>
                        
                    </div>
                    <?php
                }
            }
            else
            {
                ?>
                    <p>Services not found for this route</p>
                <?php
            }
        ?>

        <!-- Add more bus cards for different bus options here -->
    </div>

    <script src="new_booking_2.js"></script>
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