<?php
 session_start();
include "../faucet.php";

$amount = $_POST['amount'];
$address = $_SESSION['address'];
$ip = $_SERVER['REMOTE_ADDR'];

$result = $faucet->drip($address, $ip, $amount);

if(!is_string($result)){
	$ret = "An unknown error occurred";
} else {
	//everything worked out
	//$ret = "Sent ".$amount." to ".$address." Transaction id : ".$result;
	$ret = $result;
}

echo json_encode($ret);
?>
