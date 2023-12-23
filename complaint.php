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
    <title>Complaint Page</title>
    <link rel="stylesheet" href="complaint.css">
    <link rel="stylesheet" type="text/css" href="usermenu.css"  />
</head>
<body>
    <?php include("usermenu.php"); ?>
    <div class="container">
        <h1>Complaint Page</h1>
        <?php 
        if(isset($_COOKIE['success']))
        {
            echo "<p>".$_COOKIE['success']."</p>";
        }
        if(isset($_COOKIE['error']))
        {
            echo "<p>".$_COOKIE['error']."</p>";
        }
    
        function filterData($data)
        {
            return addslashes(strip_tags(trim($data)));
        }
        
        if(isset($_POST['submit']))
        {
            $busNumber = isset($_POST['busNumber']) ? filterData($_POST['busNumber']) : '';
            $userid = isset($_POST['userid']) ? filterData($_POST['userid']) : '';
            $passengerDetails = isset($_POST['passengerDetails']) ? filterData($_POST['passengerDetails']) : '';
            $issue = isset($_POST['issue']) ? filterData($_POST['issue']) : '';
            $errors = [];
					
					//username validation
					
            if($busNumber === "")
            {
                $errors['busNumber'] = "Bus Number is Required";
            }
            else
            {
                if(!filter_var($busNumber, FILTER_VALIDATE_INT))
                {
                    $errors['busNumber'] = "Bus number does not allow chars";
                }
            }

            if($passengerDetails === "")
            {
                $errors['passengerDetails'] = "Passenger Details are required";
            }
            else
            {
                if(strlen($passengerDetails) <= 10)
                {
                    $errors['passengerDetails'] = "Passenger Details should contains minimum 10 chars";
                }
            }

            if($issue === "")
            {
                $errors['issue'] = "issue Details are required";
            }
            else
            {
                if(strlen($issue) <= 10)
                {
                    $errors['issue'] = "issue Details should contains minimum 10 chars";
                }
            }

            if(count($errors) === 0)
            {
                mysqli_query($con,"insert into complaints(userid, busnum, passenger_details, description) values($userid, $busNumber, '$passengerDetails', '$issue')");
                if(mysqli_affected_rows($con) > 0)
                {
                    setcookie("success", "Your complaint registered successfully", time() + 3);
                    header("location: complaint.php");
                }
                else
                {
                    setcookie("error", "Sorry! Unable to submit the complaint, try again", time() + 3);
                    header("location: complaint.php");
                }
            }
					
        }
        ?>
        <form id="complaintForm" method='post' action="">
            <input type="hidden" value="<?php echo $login_user ?>" name="userid" />
            <label for="busNumber">Bus Number:</label>
            <input value="<?php if(isset($busNumber)) echo $busNumber; ?>" type="text" id="busNumber" name="busNumber" required>
            <span class="text-danger" id="uname_error"><?php if(isset($errors['busNumber'])) echo $errors['busNumber']; ?></span>

            <label for="passengerDetails">Passenger Details:</label>
            <input value="<?php if(isset($passengerDetails)) echo $passengerDetails; ?>" type="text" id="passengerDetails" name="passengerDetails" required>
            <span class="text-danger" id="uname_error"><?php if(isset($errors['passengerDetails'])) echo $errors['passengerDetails']; ?></span>

            <label for="issue">Describe the Issue:</label>
            <textarea id="issue" name="issue" rows="4" required><?php if(isset($issue)) echo $issue; ?></textarea>
            <span class="text-danger" id="uname_error"><?php if(isset($errors['issue'])) echo $errors['issue']; ?></span>

            <button type="submit" name="submit">Submit Complaint</button>
        </form>
        <div id="confirmationMessage" class="hidden">
            Complaint registered successfully!
        </div>
    </div>
    <script src="complaint.js"></script>
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