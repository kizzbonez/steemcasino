<?php
$gameid = $_GET['gameid'];
$player1 = $_GET['player1'];
$player2 = $_GET['player2'];
$bet = $_GET['bet'];
$reward = $_GET['reward'];
$hash = $_GET['hash'];
$secret = $_GET['secret'];

if($secret[0] == "A") {
	$winner = $player1;
	$animation = 1;
}
else {
	$winner = $player2;
	$animation = 2;
}

if($animation == 1)
	$animation = "<img style=\"width:50%\" id=\"gif\" src=\"img/animation1.gif\">";
else if($animation == 2)
	$animation = "<img style=\"width:50%\" id=\"gif\" src=\"img/animation2.gif\">";

?>
<html>
	<head>
		<title>Game number - <?php echo $gameid;?></title>
		<?php include_once('src/head.php'); ?>
	</head>
	<body>
		<center><h1><?php echo $player1." VS. ".$player2;?><br><br><?php echo $animation;?><br><br><h1 id="winner"></h1><h4 id="win"></h1><a href="#" id="close" onclick="window.close();"></a></center>
		
		<script>
			function winner() {
				$('#winner').text("Winner: <?php echo $winner;?>");
				$('#win').text("Congratulations! You have won: <?php echo $reward; ?> SBD.");
				$('#close').text("Close window");
			}
		
			setTimeout(function(){
				winner();
			}, 5000);
		</script>
	</body>
</html>