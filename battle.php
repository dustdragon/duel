<?php
require_once (dirname(__FILE__) .  '/boot.php');

$title = "Doofs Duel System BATTLE!";
$heading = "Battle!";
$totaldamage = 0;
$weapon = $_POST['Weapon'];
$bonus = $_POST['atkbonus'];
$enemy = $_POST['enemytype'];

start_html($title, $heading);

$battle = do_battle($weapon, $bonus, $enemy);
$d = $battle['d'];
$intro = $battle['intro'];
$tbp = $battle['tbp'];
$fight = $battle['fight'];
$enemyfight = $battle['enemyfight'];
$enemybonus = $battle['enemybonus'];
$weaponbp = $battle['wbp'];
//add new style class to intro header /zeus
echo '<h1 class="intro">'.$intro.'</h1>';
//add id to fight header so it will pick up on the CSS
?>
<div id="fight">
	<?php
//	echo '<div id="player-fight">';
		echo '<h2 id="fight-header">You dash forward and attack with your '.$weapon.'-1d'.$d.'!</h2>';
		foreach($fight as $k => $v) {
			
			$hit = $v['hit'];
			$dmg = $v['dmg'];
			$atk = $v['atk'];
			
			if ($bonus == 0) { 
			echo 'You roll '.$atk;
			} else {
				echo 'You roll '.$atk.$bonus;
			}
			
			switch($hit) {
				case ('critmiss'):
					echo '<br>You flail and stumble, missing the '.$enemy.' entirely.';
					break;
				case ('miss'):
					echo '<br>You attack fiercely, but your swing cuts only air.';
					break;
				case ('hit'):
					echo '<br>You strike the '.$enemy.' causing '.$dmg.' damage!';
					break;
				case ('crit'):
					echo '<br>You crush your enemies defence, dealing '.$dmg.' damage!!!';
					break;
				default:
					break;
			}
			
			$totaldamage = $totaldamage + $dmg;
			echo '<br>Total Damage: '.$totaldamage.'<br><hr>';
		}

		$bp_lost = $totaldamage * 4;
		$final_bp = $tbp - $bp_lost;

		echo '<br>';
		echo 'Enemy BP Reduced = '.$bp_lost;
		echo '<br>';
		echo 'Enemy BP Remaining = '.$final_bp.'/'.$tbp;

		if($final_bp < 1) {
			echo '<br>You Break Your Opponents Guard!';
		}
//	echo '</div>';
//	echo '<div id="enemy-fight">';
		//again, add an ID so that the H2 tag picks up on the CSS /zeus
		echo '<h2 id="enemy-fight-header"> The '.$enemy.' recovers from your attack and begins its assault! </h2>';

		//print_r($enemy_fight);
		foreach($enemyfight as $k => $v) {
			
			$hit = $v['hit'];
			$dmg = $v['dmg'];
			$atk = $v['atk'];
			
			if ($enemybonus == 0) { 
			echo 'Your opponent rolls '.$atk;
			} else {
				echo 'Your opponent rolls '.$atk.$enemybonus;
			}
			
			switch($hit) {
				case ('critmiss'):
					echo '<br>Your opponent manages to trip over thin air, nearly impaling themself and giving you a good laugh.';
					break;
				case ('miss'):
					echo '<br>The '.$enemy.' jabs at you, but you easilly parry.';
					break;
				case ('hit'):
					echo '<br>You reel back from the '.$enemy.'\'s attack, which deals '.$dmg.' damage!';
					break;
				case ('crit'):
					echo '<br>You\'re forced back as you feel the pain of '.$dmg.' damage!!!';
					break;
				default:
					break;
			}
			
			$totaldamagetoyou = $totaldamagetoyou + $dmg;
			echo '<br>Total Damage Recieved: '.$totaldamagetoyou.'<br><hr>';
		}

		$player_bp_lost = $totaldamagetoyou * 4;
		$player_final_bp = $weaponbp - $player_bp_lost;

		echo '<br>';
		echo 'Player BP Reduced = '.$player_bp_lost;
		echo '<br>';
		echo 'Player BP Remaining = '.$player_final_bp.'/'.$weaponbp;

		if($player_final_bp < 1) {
			echo '<br>Your Guard is Down!';
		}
// echo'	</div>';
echo '</div>';

page_shut();

?>
