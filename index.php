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
    <link rel="stylesheet" href="login.css">
    <title>Login Page</title>
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
				
				if(isset($_POST['login']))
				{
					$uname = isset($_POST['username']) ? filterData($_POST['username']) : '';
					
					$pass = isset($_POST['password']) ? filterData($_POST['password']) : '';
					
					
					// validations
					$errors = [];
					
					//username validation
					
					if($uname === "")
					{
						$errors['uname'] = "name is required";
					}
					// Password Validation
					if($pass === "")
					{
						$errors['pass'] = "Password is Required";
					}
					
					if(count($errors) === 0)
					{
					
						//verify the credentials
						$result = mysqli_query($con, "select id, username, email, password, role from users where username='$uname'");
							
						if(mysqli_num_rows($result)===1)
						{
							$row = mysqli_fetch_assoc($result);
							
							if(password_verify($pass, $row['password']))
							{
								
									$_SESSION['login_session_username'] = $row['username'];

									if($row['role'] === "user")
									{
                                        $_SESSION['user_session_token'] = $row['id'];
										header("location: user_dashboard.php");
									}
									else if($row['role'] === "admin")
									{
										$_SESSION['admin_session_token'] = $row['id'];
										header("location: admin_landing.php");
                                    }
							}
							else
							{
								echo "<p>Password does not matched...</p>";
							}
							
						}
						else
						{
							echo "<p>Email id does not matched...</p>";
						}
					}
					
				}
                mysqli_close($con);
			?>
        <form action="" method="POST">
            <h1>Login</h1>
            <label for="username">Username:</label>
            <input type="text" value="<?php if(isset($uname)) echo $uname; ?>" id="username" name="username" required>
            <span class="" id="uname_error"><?php if(isset($errors['uname'])) echo $errors['uname']; ?></span>

            <label for="password">Password:</label>
            <input value="<?php if(isset($pass)) echo $pass; ?>" type="password" id="password" name="password" required>
            <span class="" id="pwd_error"><?php if(isset($errors['pass'])) echo $errors['pass']; ?></span>

            <button name="login" type="submit">Login</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>