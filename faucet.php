<?php

/*
CREATE TABLE users
(
id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
address VARCHAR(34) NOT NULL,
ip VARCHAR(15) NOT NULL,
amount DOUBLE NOT NULL,
time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id)
) ENGINE = InnoDB;

*/
function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

include "config.php";
include "bitcoin.php";

class BitcoinFaucet
{
    public $config = array();
    public $client;
    public $mysql;
    public $sent_amount;

    public function loadConfig($config)
    {
        $this->client = new BitcoinClient($config['rpc_method'], $config['rpc_user'], $config['rpc_pass'], $config['rpc_host'], $config['rpc_port']);
        $this->mysql = new mysqli($config['mysql_host'], $config['mysql_user'], $config['mysql_pass'], $config['mysql_db']);

        if (mysqli_connect_errno())
        {
            printf("Can't connect to MySQL Server. Errorcode: %s\n", mysqli_connect_error());
            exit;
        }

        $config['balance'] = $this->client->getbalance($config['account_name']);

        $this->config = $config;
    }

    function getLastTimeSent($address, $ip)
    {
    	//if ($ip = "127.0.0.1") return;
        $stmt = $this->mysql->prepare("SELECT time FROM users WHERE `address` = ? OR `ip` = ? ORDER BY `id` DESC LIMIT 1");
        if(!($stmt && $stmt->bind_param("ss", $address, $ip) && $stmt->execute() && $stmt->store_result()))
        {
            printf("Can't query MySQL (1): Errorcode: %s\n", $this->mysql->error);
            exit;
        }

        if($stmt->num_rows <= 0) return 0;

        $stmt->bind_result($time);
        $stmt->fetch();
	    $stmt->free_result();
        return strtotime($time);
    }

    function sendToAddress($address, $ip, $amount)
    {
        $stmt = $this->mysql->prepare("INSERT INTO users (address, ip, amount) VALUES (?,?,?)");
        if(!($stmt && $stmt->bind_param("ssd", $address, $ip, $amount) && $stmt->execute()))
        {
            printf("Can't query MySQL (2): Errorcode: %s\n", $this->mysql->error);
            exit;
        }
	$stmt->free_result();
	if($this->config['testing']) return "0000000000000T3STING000000000000000";
    return $this->client->sendfrom($this->config['account_name'], $address, $amount);
    }

    public function check($address, $ip) 
    {
        if(!Bitcoin::checkAddress($address, $this->config['address_ver']))
            return 1;
        $last = $this->getLastTimeSent($address, $ip);
       
        if(time() - $last < $this->config['time_limit'])
            return 2;

        if($this->config['account_limit'] >= $this->config['balance'])
            return 3; // account empty

        //address, ip OK
        return 0;
    }

    public function drip($address, $ip, $amount=0)
    {
        $error = $this->check($address, $ip);
        if ($error > 0) return $error;

        if ($amount > 0) {
            $this->sent_amount = $amount;
        } else {
            srand(time());
            $rand = $this->config['min_amount'] + mt_rand() / mt_getrandmax() * ($this->config['max_amount'] - $this->config['min_amount']);
            $this->sent_amount = round($rand,1);
        }
        
        $txid = $this->sendToAddress($address, $ip, $this->sent_amount );
        if(!$txid)
            return 4;

        return $txid;
    }
}

$faucet = new BitcoinFaucet();
$faucet->loadConfig($CONFIG);


?>
