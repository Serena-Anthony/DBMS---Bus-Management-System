<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']) && !empty($_SESSION['user_session_token']))
{
    include("connect.php");
    $login_user = $_SESSION['user_session_token'];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View History</title>
    <link rel="stylesheet" href="user_vh.css">
    <link rel="stylesheet" type="text/css" href="usermenu.css"  />
</head>
<body>
    <?php include("usermenu.php");?>
    <div class="container">
        <h1>View Travel History</h1>
        <?php 
            $bookingResults = mysqli_query($con, "select * from bookings where userid=$login_user");
            if(mysqli_num_rows($bookingResults)>0)
            {
                
                    while($row = mysqli_fetch_assoc($bookingResults))
                    {
                        $busno = $row['busnum'];
                        $busInfo = mysqli_query($con, "select destination from routes where busnum=$busno");
                        $busrow = mysqli_fetch_assoc($busInfo);
                        ?>
                            <div class="travel-entry <?php if($row['status'] === "cancel") echo "cancel-bg"; else echo "okay-bg"?>">
                            <?php if($row['status'] === "cancel") {?>    
                                <p class='cancelled'>Cancelled</p>
                            <?php }?>
                                <p><strong>Service Number:</strong> <?php echo $row['busnum'] ?></p>
                                <p><strong>Date:</strong> <?php echo $row['travel_date'] ?></p>
                                <p><strong>Details:</strong> Trip to <?php echo $busrow['destination'] ?></p>
                            </div>
                        <?php
                    }
               
            }
            else
            {
                ?>
                    <p>No History Found</p>
                <?php
            }
        ?>
    </div>
    <script src="user_vh.js"></script>
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