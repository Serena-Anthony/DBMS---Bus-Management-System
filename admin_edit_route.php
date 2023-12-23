<?php 
ob_start();
session_start();
if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    include("connect.php");
    if(!(isset($_REQUEST['bid']) && !empty($_REQUEST['bid'])))
    {
        header("Location: admin_view_routes.php");
    }
    
    $bid = $_REQUEST['bid'];
    $routeInfo = mysqli_query($con, "select * from routes where id=$bid");

    if(mysqli_num_rows($routeInfo) === 1)
    {
        $rinfo = mysqli_fetch_assoc($routeInfo);
    }
    else{
        header("Location: admin_view_routes.php");
    }
    
    ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Edit Route</title>
    <link rel="stylesheet" type="text/css" href="admin_mangage_routes.css" />
    <link rel="stylesheet" type="text/css" href="usermenu.css" />
    <!-- Link to your external CSS file -->
  </head>
  <body >
    <?php include("adminmenu.php"); ?>
    <div class="form-container">
      <div class="add-route">
      <h1>Edit Route</h1>
      <?php 
        if(isset($_COOKIE['success']))
        {
          echo "<p class='success'>".$_COOKIE['success']."</p>";
        }
        if(isset($_COOKIE['error']))
        {
          echo "<p class='error'>".$_COOKIE['error']."</p>";
        }
      
        function filterData($data)
        {
          return addslashes(strip_tags(trim($data)));
        }

        if(isset($_POST['submit']))
        {
          
          $editSource = isset($_POST['editSource']) ? filterData($_POST['editSource']) : '';
          $editDestination = isset($_POST['editDestination']) ? filterData($_POST['editDestination']) : '';
          $status = isset($_POST['status']) ? filterData($_POST['status']) : '';
          $endTime = isset($_POST['endTime']) ? filterData($_POST['endTime']) : '';
          $startTime = isset($_POST['startTime']) ? filterData($_POST['startTime']) : '';
          $totalSeats = isset($_POST['totalSeats']) ? filterData($_POST['totalSeats']) : '';
          $seatCharge = isset($_POST['seatCharge']) ? filterData($_POST['seatCharge']) : '';
          // validations
          $errors = [];
					
					
          if($editSource === "")
          {
              $errors['editSource'] = "source is required";
          }
          if($editDestination === "")
          {
              $errors['editDestination'] = "destination is required";
          }
          if($status === "")
          {
              $errors['status'] = "Status is required";
          }

            if($startTime === "")
            {
                    $errors['startTime'] = "Start time is required";
            }
            if($endTime === "")
            {
                    $errors['endTime'] = "End time is required";
            }
            if($seatCharge === "")
            {
                    $errors['seatCharge'] = "Seat Charge is required";
            }
            else
            {
                if(!filter_var($seatCharge, FILTER_VALIDATE_INT))
                {
                        $errors['seatCharge'] = "Seat Charge does not allowed chars";
                }
            }

            if($totalSeats === "")
            {
                    $errors['totalSeats'] = "Total seats are required";
            }
            else
            {
                if(!filter_var($totalSeats, FILTER_VALIDATE_INT))
                {
                        $errors['totalSeats'] = "Total seats does not allowed chars";
                }
            }

          if(count($errors) == 0)
          {
              mysqli_query($con, "UPDATE routes SET source='$editSource', destination='$editDestination', status='$status', start_time='$startTime', end_time='$endTime', total_seats='$totalSeats', charge='$seatCharge' where id=$bid");

              if(mysqli_affected_rows($con) === 1)
              {
                  setcookie("success", "Bus route updated successfully", time() + 3);
                  header("location: admin_edit_route.php?bid=".$bid);
              }
              else{
                setcookie("error", "Sorry! Unable to update bus routes", time() + 3);
                header("location: admin_edit_route.php?bid=".$bid);
              }
          }

        }
      ?>

      <?php 
        if(isset($errors['checkbus']))
        {
          echo "<p class='error'>".$errors['checkbus']."</p>";
        }
      ?>

      <form method="POST" action="">

        <label for="busNumber">Editing Bus Number : <?php echo $rinfo['busnum']; ?></label>
        <hr />
        
        <span class="text-danger" id=""><?php if(isset($errors['busNumber'])) echo $errors['busNumber']; ?></span>

        <label for="editSource">Edit Source:</label>
        <input type="text" value="<?php echo $rinfo['source']; ?>" id="editSource" name="editSource" />
        <span class="text-danger" id=""><?php if(isset($errors['editSource'])) echo $errors['editSource']; ?></span>

        <label for="editDestination">Edit Destination:</label>
        <input value="<?php echo $rinfo['destination']; ?>" type="text" id="editDestination" name="editDestination" />
        <span class="text-danger" id=""><?php if(isset($errors['editDestination'])) echo $errors['editDestination']; ?></span>

        <label for="startTime">Start Time:</label>
        <input value="<?php echo $rinfo['start_time']; ?>" type="text" id="startTime" name="startTime" />
        <span class="text-danger" id=""><?php if(isset($errors['startTime'])) echo $errors['startTime']; ?></span>

        <label for="endTime">End Time:</label>
        <input value="<?php echo $rinfo['end_time']; ?>" type="text" id="endTime" name="endTime" />
        <span class="text-danger" id=""><?php if(isset($errors['endTime'])) echo $errors['endTime']; ?></span>

        <label for="totalSeats">Total Seats:</label>
        <input value="<?php echo $rinfo['total_seats']; ?>" type="text" id="totalSeats" name="totalSeats" />
        <span class="text-danger" id=""><?php if(isset($errors['totalSeats'])) echo $errors['totalSeats']; ?></span>

        <label for="seatCharge">Charge / Seat Fare:</label>
        <input value="<?php echo $rinfo['charge']; ?>" type="text" id="seatCharge" name="seatCharge" />
        <span class="text-danger" id=""><?php if(isset($errors['seatCharge'])) echo $errors['seatCharge']; ?></span>

        <label for="status">Status:</label>
        <select id="status" name="status">
          <option <?php if($rinfo['status'] === 'Active') echo "selected"; ?> value="Active">Active</option>
          <option <?php if($rinfo['status'] === 'Deleted') echo "selected"; ?> value="Deleted">Deleted</option>
        </select>
        <span class="text-danger" id=""><?php if(isset($errors['status'])) echo $errors['status']; ?></span>

        <button type="submit" name="submit"
          class="submit-button"
          id="statusButton"
          onclick="toggleStatus()">
          Update
        </button>
      </form>
    </div>

    

    </div>
    
     
      
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