<?php
include_once('src/config.php');

include_once('src/db.php');

include_once('src/head.php');

include_once('src/coinfliputils.php');

include_once('src/utils.php');

if(isset($_GET['game'])) {
	if(!$_GET['game'] == NULL) {
		if($_GET['game'] == 0)
			die("Invalid gameID.");
			$query = $db->prepare('SELECT * FROM users WHERE username = ?');
			$query->bind_param('s', $_COOKIE['username']);
	
			$query->execute();
			
			$result = $query->get_result();
			if($result->num_rows) {
				while ($row = $result->fetch_assoc()) { 
					$balanced = $row['balance'];
					$thiswon = $row['won'];
					$thislost = $row['losted'];
				}
				if(IsLoggedOnUser()) {
					$query = $db->prepare('SELECT * FROM coinflip WHERE ID = ?');
					$query->bind_param('i', $_GET['game']);
							
					$query->execute();
					$result = $query->get_result();
					
					if(!$result->num_rows)
						die("Invalid gameID.");
					
					while ($row = $result->fetch_assoc()) { 
						$bet = $row['bet'];
						$player1 = $row['player1'];
						$reward = $row['reward'];
						$player2 = $row['player2'];
						$secret = $row['secret'];
					}
					
					if($balanced < $bet)
						die("You don't have enough money!");
					
					if($player2 != "" && $player1 != "")
						die("Game has already ended.");
					
					if($player2 == "") {
						$playered = 2;
						if($player1 == $_COOKIE['username'])
							die("You can't play in your own games!");
						$otherplayer = $player1;
					}
					else {
						$playered = 1;
						if($player2 == $_COOKIE['username'])
							die("You can't play in your own games!");
						$otherplayer = $player2;
					}
					
					if($secret[0] == "A")
						$win = 1;
					else if($secret[0] == "B")
						$win = 2;
					
					$timestamp = time();
					
					$query = $db->prepare('UPDATE coinflip SET player'.$playered.' = ?, win = ?, timestamp = ? WHERE ID = ?');
					$query->bind_param('siii', $_COOKIE['username'], $win, $timestamp, $_GET['game']);
					
					$query->execute();
					
					$query = $db->prepare('SELECT * FROM users WHERE username = ?');
					$query->bind_param('s', $otherplayer);
						
					$query->execute();
					$result = $query->get_result();
					while ($row = $result->fetch_assoc()) { 
						$otherbalance = $row['balance'];
						$otherwon = $row['won'];
						$otherlost = $row['losted'];
					}
					
					if($playered == $win)
					{
						$newbalance = $balanced + $bet;
						$thiswon = $thiswon + $bet;
						$otherlost = $otherlost + $bet;
						
					} else{
						$newbalance = $balanced - $bet;
						$thislost = $thislost + $bet;
						$otherwon = $otherwon + $bet;
						$otherbalance = $otherbalance + $reward;
					}
					
					$query = $db->prepare('UPDATE users SET balance = ?, won = ?, losted = ? WHERE username = ?');
					$query->bind_param('ddds', $otherbalance, $otherwon, $otherlost, $otherplayer);
						
					$query->execute();	
					
					$query = $db->prepare('UPDATE users SET balance = ?, won = ?, losted = ? WHERE username = ?');
					$query->bind_param('ddds', $newbalance, $thiswon, $thislost, $_COOKIE['username']);
					
					$query->execute();
					
					echo "<script>
							window.onunload = refreshParent;
							function refreshParent() {
								window.opener.location.reload();
							}
							window.close();
						</script>";				
				} else
					die("Your session is invalid. Please relog.");
			} else 
				die("Your session is invalid. Please relog.");
				
				
	} else {
		die("Invalid gameID.");
	}
} else {
	die("Invalid gameID.");
}
?>