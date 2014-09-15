<?php
include "faucet.php";
require_once('includes/recaptchalib.php');


$error = false;
$success = false;


if(isset($_POST['submit'])){
	$resp = recaptcha_check_answer($faucet->config['recaptcha_private'],$_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	//$resp->is_valid = true; //TESTING!
	
	if($resp->is_valid){
		//The CAPTCHA was good, let's get on
		$address = $_POST['address'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$result = $faucet->drip($address, $ip);

		if(!is_string($result)){
			$error = true;
			switch($result){
				case 1:
					$error_msg = "The address you entered is invalid.";
					break;
				case 2:
					$error_msg = "Sorry, but you've already had coins in the last 2 hours.";
					break;
				case 3:
					$error_msg = "There aren't enough coins in the faucets wallet for us to drip you some.";
					break;
				case 4:
					$error_msg = "There was an error sending the coins.";
				default:
					$error_msg = "An unknown error occurred";
			}
		}else{
			//Whoohoo, everything worked out
			$success = true;
		}
	}else{
		$error = true;
		$error_msg = "Your CAPTCHA was invalid, please try again.";
	}
}
?>
<!doctype html>
<html>
	<head>
		<title> <?php echo $coin_name; ?> &raquo; Faucet</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<section id="faucet">
			<nav>
				<div class="container">
					<span class="brand">
						<img src="img/logo.png"> <?php echo $coin_name; ?>
					</span>
					<span class="page-title">
						Faucet
					</span>
				</div>
			</nav>
			<div id="content">
				<div class="container">
					<p class="lead">
					 <small>Enter your address below to win between <?php echo $faucet->config['min_amount']." and ".$faucet->config['max_amount']." ".$coin_unit; ?>!</small>
					</p>
					<?php
						if($error){
							echo "<p class='text-danger'>$error_msg</p>";
						}
						if($success){
							echo "<p class='text-success'>You got ".$faucet->sent_amount." ".$coin_unit."! Your transaction id is <a href=".$explorer_url."/tx/".$result.">".$result."</a></p>";
						}else{
					?>
					 <form action="index.php" method="POST" class="form-horizontal center-block" role="form">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Wallet address</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="inputWallet" name="address" placeholder="Your <?php echo $coin_name;?>  address">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Enter captcha:</label>
							<div class="col-sm-9">
								<?php 
								echo recaptcha_get_html($CONFIG['recaptcha_public']); 
								?>
								<!--div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div-->
							</div>
						</div>
						
						
						
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<button type="submit" class="btn btn-default" name="submit" id="submit">Send coins</button>
							</div>
						</div>
						
						
					</form>
					<?php
					}
					?>
				</div>
			</div>
		</section>
	</body>
</html>
