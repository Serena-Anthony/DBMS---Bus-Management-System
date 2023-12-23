<?php 
ob_start();
session_start();
if(isset($_SESSION['user_session_token']))
{
    header("location: user_dashboard.php");
}

if(isset($_SESSION['admin_session_token']))
{
    header("Location: admin_landing.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>Signup Page</title>
</head>
<body>
    <div class="container">
    <?php 
				
                include("connect.php");

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
					$uname = isset($_POST['uname']) ? filterData($_POST['uname']) : '';
					$email = isset($_POST['email']) ? filterData($_POST['email']) : '';
					$pass = isset($_POST['pwd']) ? filterData($_POST['pwd']) : '';
					$gender = isset($_POST['gender']) ? filterData($_POST['gender']) : '';
					$mobile = isset($_POST['mobile']) ? filterData($_POST['mobile']) : '';
					
					// validations
					$errors = [];
					
					//username validation
					
					if($uname === "")
					{
						$errors['uname'] = "name is required";
					}
					else
					{
						$result = mysqli_query($con, "select username from users where username='$uname'");
						if(mysqli_num_rows($result) == 1)
						{
							$errors['uname'] = "Username already taken, choose another username";
						}
						if(strlen($uname) <= 3)
						{
							$errors['uname'] = "name should contains minimum 4 chars";
						}
					}
					
					// email validation
					if($email === "")
					{
						$errors['email'] = "email is Required";
					}
					else
					{

						if(!filter_var($email, FILTER_VALIDATE_EMAIL))
						{
							$errors['email'] = "Valid email is required";
						}
						$eresult = mysqli_query($con, "select email from users where email='$email'");
						if(mysqli_num_rows($eresult) === 1)
						{
							$errors['email'] = "email id already taken choose another";
						}
					}
					
					// Password Validation
					if($pass === "")
					{
						$errors['pass'] = "Password is Required";
					}
					else
					{
						$pattern = "/(?=^.{6,}$)(?=.{0,}[A-Z])(?=.{0,}[a-z])(?=.{0,}\W)(?=.{0,}\d)/";
						
						if(!preg_match($pattern, $pass))
						{
							$errors['pass'] = "Password should contains Minimum length 8 characters, 1 uppercase,1 lowercase, 1 special character, 1 digit";
						}
					}
					// mobile validation
					if($mobile === "")
					{
						$errors['mobile'] = "Mobile is Required";
					}
					else
					{
						if(!filter_var($mobile, FILTER_VALIDATE_INT))
						{
							$errors['mobile'] = "Mobile number does not allow chars";
						}
						else if(strlen($mobile) !== 10 )
						{
							$errors['mobile'] = "Enter 10 digits mobile number";
						}
					}

					// gender Validation
					if($gender === "")
					{
						$errors['gender'] = "Gender is Required";
					}
					
					if(count($errors) === 0)
					{
					
						$token = md5(str_shuffle('qwertyuioplkjhgfdsazxcvbnm'.time().$uname.$mobile));
						$ip = $_SERVER['REMOTE_ADDR'];
						
						$hash_pass = password_hash($pass, PASSWORD_DEFAULT);
						
						mysqli_query($con, "INSERT INTO users(username, email, password, mobile, gender) VALUES('$uname', '$email', '$hash_pass','$mobile', '$gender')");
						
						if(mysqli_affected_rows($con)===1)
						{
                            setcookie("success", "Account created successfully", time()+3);
                            header("Location: signup.php");
						}
						else
						{	
							setcookie("error", "Sorry! unbale to create an account try again", time()+3);
							header("Location: signup.php");
						}
					}
					
				}
                mysqli_close($con);
			?>
        <form action="" method="POST">
            <h1>Sign Up</h1>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="uname" required value="<?php if(isset($uname)) echo $uname; ?>">
            <span class="text-danger" id="uname_error"><?php if(isset($errors['uname'])) echo $errors['uname']; ?></span>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="mobile" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php if(isset($email)) echo $email; ?>" required>
            <span class="text-danger" id="email_error"><?php if(isset($errors['email'])) echo $errors['email']; ?></span>

            <label for="password">Password:</label>
            <input type="password" id="email" value="<?php if(isset($pass)) echo $pass; ?>" name="pwd" required>
            <span class="text-danger" id="pwd_error"><?php if(isset($errors['pass'])) echo $errors['pass']; ?></span>

            <label>Gender:</label>
            <div class="gender-options">
                <label>Gender:</label>
					<label><input <?php if(isset($gender)) if($gender === "Male") echo "checked"; ?>  type="radio" name="gender" value="Male" />Male </label>

					<label><input <?php if(isset($gender)) if($gender === "Female") echo "checked"; ?> type="radio" name="gender" value="Female" />Female</label>

					<label><input <?php if(isset($gender)) if($gender === "Others") echo "checked"; ?> type="radio" name="gender" value="Others" />Others</label>

					<span class="text-danger" id="gender_error"><?php if(isset($errors['gender'])) echo $errors['gender']; ?></span>
            </div>
            
            <button name="submit" type="submit">Sign Up</button>
            
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>