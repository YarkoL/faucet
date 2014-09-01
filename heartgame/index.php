<!DOCTYPE html>
<?php
 include "../faucet.php";
 $error = "";
 session_start();
 unset($_SESSION['address']);
?>
<html>    
<head>
  <meta charset="utf-8" />
  <title>Leaps Of LOVE</title>
  <style type="text/css">
    p {
    	font-family: Arial, Helvetica, sans-serif
	}
    body {
      background-color: #eee;
    }
    .outer {
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
    }

    .middle {
        display: table-cell;
        vertical-align: middle;
    }

    .inner {
        margin-left: auto;
        margin-right: auto; 
    }
  </style>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-51155101-2', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body>
  
<?php

if(isset($_GET['address'])){
  $result = $faucet->check($_GET['address'], $_SERVER["REMOTE_ADDR"]);
  if($result > 0) {
    switch($result){
      case 1:
        $error = "The address you entered is invalid.";
        break;
      case 2:
        $error = "Sorry, but you've already had coins in the last 12 hours.";
        break;
      case 3:
        $error = "There aren't enough coins in the faucets wallet for us to drip you some.";
        break;
      default:
        $error = "An unknown error occurred";
    } 
    unset($_GET['address']);
  } else {
    $_SESSION['address'] = $_GET['address'];
?>
    <script type="text/javascript" src="phaser.min.js"></script>
    <script type="text/javascript" src="boot.js"></script>
    <script type="text/javascript" src="preload.js"></script>
    <script type="text/javascript" src="howto.js"></script> 
    <script type="text/javascript" src="main.js"></script>
    <script type="text/javascript" src="goodbye.js"></script>
    <script type="text/javascript"> 
    (function() {
      var gAmount = 0;
      var game = new Phaser.Game(400, 490, Phaser.CANVAS, 'game');
      game.state.add('Boot', Heart.Boot);
      game.state.add('Preloader', Heart.Preloader);
      game.state.add('HowTo', Heart.HowTo);
      game.state.add('Game', Heart.Game);
      game.state.add('Bye', Heart.Bye);
      game.state.start('Boot');
    })();
    </script> 

<?php
  } 
}

if(!isset($_SESSION['address'])) {

?> 
  <div align = "center"></div>
  <div class="outer">
  <div class="middle">
  <div class="inner">
  <form action="index.php" method="GET" id="address-form" >
       <div align = "center">
        <img src = "assets/splash.png"/><br/>
          <input type="text"  id="inputWallet" name="address" placeholder="Your Love Address">
       </div>
      <div align = "center">
        <button type="submit" name="submit" id="submit">Start game</button>
      </div> 
      <div align = "center">
        <p><?php echo $error ?></p>
      </div>  
      <div align = "center">
        <p>Don't have a LOVE address?<br/><a href="http://lovecoins.info">Get the wallet here!</a></p>
      </div>            
  </form>
  </div>
  </div>
  </div>
<?php
} /* else { 
  die("OOPS Something went wrong with address ".$_SESSION['address']);
} */
?> 

</body>

</html>
