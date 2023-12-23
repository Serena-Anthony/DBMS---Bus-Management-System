<?php 
ob_start();
session_start();
if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    include("connect.php");
    ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Manage Services</title>
    <link rel="stylesheet" type="text/css" href="admin_mangage_routes.css" />
    <link rel="stylesheet" type="text/css" href="usermenu.css" />
    <!-- Link to your external CSS file -->
  </head>
  <body >

    <?php  include("adminmenu.php");?>

    <div class="form-container">
      <h1 class="page-title">Add Route</h1>
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
          $busNumber = isset($_POST['busNumber']) ? filterData($_POST['busNumber']) : '';
          $editSource = isset($_POST['editSource']) ? filterData($_POST['editSource']) : '';
          $editDestination = isset($_POST['editDestination']) ? filterData($_POST['editDestination']) : '';
          $status = isset($_POST['status']) ? filterData($_POST['status']) : '';
          $endTime = isset($_POST['endTime']) ? filterData($_POST['endTime']) : '';
          $startTime = isset($_POST['startTime']) ? filterData($_POST['startTime']) : '';
          $totalSeats = isset($_POST['totalSeats']) ? filterData($_POST['totalSeats']) : '';
          $seatCharge = isset($_POST['seatCharge']) ? filterData($_POST['seatCharge']) : '';
          // validations
          $errors = [];
					
					//username validation
					
					if($busNumber === "")
					{
						  $errors['busNumber'] = "Bus Number is required";
					}
					else
					{
						if(!filter_var($busNumber, FILTER_VALIDATE_INT))
						{
							  $errors['busNumber'] = "Bus number does not allow chars";
						}
					}
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
          
          if($busNumber)
          {
            $checkBusResult = mysqli_query($con, "select busnum from routes where busnum=$busNumber");
            if(mysqli_num_rows($checkBusResult) === 1)
            {
              $errors['checkbus'] = $busNumber." is already exists, choose another number";
            }
          }
         


          if(count($errors) == 0)
          {
              mysqli_query($con, "INSERT INTO routes(busnum, source, destination, status, start_time, end_time, total_seats, charge ) VALUES('$busNumber', '$editSource', '$editDestination', '$status', '$startTime', '$endTime', '$totalSeats', '$seatCharge')");
              if(mysqli_affected_rows($con) === 1)
              {
                  setcookie("success", "Bus route added successfully", time() + 3);
                  header("location: admin_manage_routes.php");
              }
              else{
                setcookie("error", "Sorry! Unable to add bus routes", time() + 3);
                header("location: admin_manage_routes.php");
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
        <div class="formgroup">
          <label for="busNumber">Bus Number:</label>
          <input type="text" value="<?php if(isset($busNumber)) echo $busNumber; ?>" id="busNumber" name="busNumber" />
          <span class="text-danger" id=""><?php if(isset($errors['busNumber'])) echo $errors['busNumber']; ?></span>
        </div>

        <div class="formgroup">
          <label for="editSource">Source:</label>
          <input type="text" value="<?php if(isset($editSource)) echo $editSource; ?>" id="editSource" name="editSource" />
          <span class="text-danger" id=""><?php if(isset($errors['editSource'])) echo $errors['editSource']; ?></span>
        </div>

        <div class="formgroup">
          <label for="editDestination">Destination:</label>
          <input value="<?php if(isset($editDestination)) echo $editDestination; ?>" type="text" id="editDestination" name="editDestination" />
          <span class="text-danger" id=""><?php if(isset($errors['editDestination'])) echo $errors['editDestination']; ?></span>
        </div>

        <div class="formgroup">
          <label for="startTime">Start Time:</label>
          <input value="<?php if(isset($startTime)) echo $startTime; ?>" type="text" id="startTime" name="startTime" />
          <span class="text-danger" id=""><?php if(isset($errors['startTime'])) echo $errors['startTime']; ?></span>
        </div>

        <div class="formgroup">
          <label for="endTime">End Time:</label>
          <input value="<?php if(isset($endTime)) echo $endTime; ?>" type="text" id="endTime" name="endTime" />
          <span class="text-danger" id=""><?php if(isset($errors['endTime'])) echo $errors['endTime']; ?></span>
        </div>

        <div class="formgroup">
          <label for="totalSeats">Total Seats:</label>
          <input value="<?php if(isset($totalSeats)) echo $totalSeats; ?>" type="text" id="totalSeats" name="totalSeats" />
          <span class="text-danger" id=""><?php if(isset($errors['totalSeats'])) echo $errors['totalSeats']; ?></span>
        </div>

        <div class="formgroup">
          <label for="seatCharge">Charge / Seat Fare:</label>
          <input value="<?php if(isset($seatCharge)) echo $seatCharge; ?>" type="text" id="seatCharge" name="seatCharge" />
          <span class="text-danger" id=""><?php if(isset($errors['seatCharge'])) echo $errors['seatCharge']; ?></span>
        </div>
        
        <div class="formgroup">
          <label for="status">Status:</label>
          <select id="status" name="status">
            <option <?php if(isset($status)) if($status === "Active") echo "selected"; ?> value="Active">Active</option>
            <option <?php if(isset($status)) if($status === "Deleted") echo "selected"; ?> value="Deleted">Deleted</option>
          </select>
          <span class="text-danger" id=""><?php if(isset($errors['status'])) echo $errors['status']; ?></span>
        </div>

        <div class="formgroup">
          <button type="submit" name="submit"
            class="submit-button"
            id="statusButton"
            onclick="toggleStatus()">
            Submit
          </button>
        </div>
      </form>

    

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