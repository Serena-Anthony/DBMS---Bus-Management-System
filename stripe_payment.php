<?php 
    /* coded by Rahul Barui ( https://github.com/Rahul-Barui ) */
    include "connect.php";

	/*print "<pre>";
	print_r($_POST);
	var_dump($_POST);
	die;*/

	$payment_id = $statusMsg = ''; 
	$ordStatus = 'error';
	$id = '';

	// Check whether stripe token is not empty

	if(!empty($_POST['stripeToken'])){

		// Get Token, Card and User Info from Form
		$token = $_POST['stripeToken'];
		$name = $_POST['holdername'];
		$email = $_POST['email'];
		$card_no = $_POST['card_number'];
		$card_cvc = $_POST['card_cvc'];
		$card_exp_month = $_POST['card_exp_month'];
		$card_exp_year = $_POST['card_exp_year'];

		// Get Product ID From - Form
		$tid = $_POST['tid'];

		
		$unitCharge = mysqli_query($con, "select routes.charge from routes INNER JOIN bookings ON routes.busnum = bookings.busnum where transactionid='$tid'");
    	$row = mysqli_fetch_assoc($unitCharge);

		$totalSeats = mysqli_query($con, "select count(*) as total from bookings where transactionid='$tid' and status = 'booked'");
		
		$trow = mysqli_fetch_assoc($totalSeats);
		
	    $price = $row['charge'] * $trow['total'];

	    $pr_desc = $_POST['username'];
		


		// Include STRIPE PHP Library
		require_once('stripe-php/init.php');

		// set API Key
		$stripe = array(
		"SecretKey"=>"sk_test_mOrcHTINcIgblg2D70rirhDF",
		"PublishableKey"=>"pk_test_B6cJ6r3MAZkwZdSvfHLVUffG"
		);

		// Set your secret key: remember to change this to your live secret key in production
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey($stripe['SecretKey']);

		// Add customer to stripe 
	    $customer = \Stripe\Customer::create(array( 
	        'email' => $email, 
	        'source'  => $token,
	        'name' => $name,
	        'description'=>$pr_desc
	    ));

	    // Generate Unique order ID 
	    $orderID = strtoupper(str_replace('.','',uniqid('', true)));
	     
	    // Convert price to cents 
	    $itemPrice = ($price * 100);
	    $currency = "inr";
	    $itemName = "Bus Ticket Booking Details-".$tid;

	    // Charge a credit or a debit card 
	    $charge = \Stripe\Charge::create(array( 
	        'customer' => $customer->id, 
	        'amount'   => $itemPrice, 
	        'currency' => $currency, 
	        'description' => $itemName, 
	        'metadata' => array( 
	            'order_id' => $orderID 
	        ) 
	    ));

	    // Retrieve charge details 
    	$chargeJson = $charge->jsonSerialize();

    	// Check whether the charge is successful 
    	if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){ 

	        // Order details 
	        $transactionID = $chargeJson['balance_transaction']; 
	        $paidAmount = ($chargeJson['amount'] / 100); 
	        $paidCurrency = $chargeJson['currency']; 
	        $payment_status = $chargeJson['status'];
	        $payment_date = date("Y-m-d H:i:s");
	        $dt_tm = date('Y-m-d H:i:s');

	        // Insert tansaction data into the database

	        $sql = "INSERT INTO `orders`(`name`,`email`,`card_number`,`card_exp_month`,`card_exp_year`,`item_name`,`ticket_trans_number`,`total_price`,`price_currency`,`paid_amount`,`paid_amount_currency`,`txn_id`,`payment_status`,`created`,`modified`) VALUES ('$name','$email','$card_no','$card_exp_month','$card_exp_year','$itemName','$tid','$itemPrice','$currency','$paidAmount','$paidCurrency','$transactionID','$payment_status','$dt_tm','$dt_tm')";
	        mysqli_query($con,$sql) or die("Mysql Error Stripe-Charge(SQL)".mysqli_error($con));

			// update bookings table
			if($payment_status == "succeeded" )
			{
				mysqli_query($con, "UPDATE bookings SET status='booked', payment='success', payment_id='$transactionID' where transactionid='$tid'");
			}
			else
			{
				mysqli_query($con, "UPDATE bookings SET status='cancel', payment='failed', payment_id='$transactionID' where transactionid='$tid'");
			}
			

    		//Get Last Id
    		$sql_g = "SELECT * FROM `orders`";
    		$res_g = mysqli_query($con,$sql_g) or die("Mysql Error Stripe-Charge(SQL2)".mysqli_error($con));
    		while($row_g=mysqli_fetch_assoc($res_g)){
    			$id = $row_g['id'];
    		}

	        // If the order is successful 
	        if($payment_status == 'succeeded'){ 
	            $ordStatus = 'success'; 
	            $statusMsg = 'Your Payment has been Successful!'; 
	    	} else{ 
	            $statusMsg = "Your Payment has Failed!"; 
	        } 
	    } else{ 
	        //print '<pre>';print_r($chargeJson); 
	        $statusMsg = "Transaction has been failed!"; 
	    } 
	} else{ 
	    $statusMsg = "Error on form submission."; 
	} 
	
?>

<!DOCTYPE html>
<html>
	<head>
        <title> Stripe Payment Gateway Integration in PHP </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="payment.css">
    </head>

    <div class="container">
        <h2 style="text-align: center; color: blue;">Booking Details Confirmation</h2>
        
        <br>
        <div class="row">
	        <div class="col-lg-12">
				<div class="status">
					<h1 class="<?php echo $ordStatus; ?>"><?php echo $statusMsg; ?></h1>
					<h4 class="heading">Payment Information - </h4>
					<br>
					<p><b>Reference ID:</b> <strong><?php echo $id; ?></strong></p>
					<p><b>Transaction ID:</b> <?php echo $transactionID; ?></p>
					<p><b>Paid Amount:</b> <?php echo $paidAmount.' '.$paidCurrency; ?> (Rs.<?php echo $price;?>.00)</p>
					<p><b>Payment Status:</b> <?php echo $payment_status; ?></p>
					<h4 class="heading">Product Information - </h4>
					<br>
					<p><b>Name:</b> <?php echo $itemName; ?></p>
					<p><b>Price:</b> Rs.<?php echo $price;?>.00</p>
				</div>
				<a href="user_manage_booking.php" class="btn-continue">Back to Home</a>
			</div>
		</div>
	</div>
</html>