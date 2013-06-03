<?php

require_once (dirname(__FILE__) .  '/boot.php');

$title = "Doofs Duel System";
$heading = "Choose your Duel!";

start_html($title, $heading);

$wsql = 'SELECT type FROM weapons';
$weapons = get_stuff_from_db($wsql);

$esql = 'SELECT name FROM enemy';
$enemies = get_stuff_from_db($esql);
echo '<br>';

?>
	<?php
	echo 'Weapon';
	?>
	<form action="battle.php" method="POST">
	<select name="Weapon" id="weaponChoice">
	<?php
	foreach($weapons as $weapon => $stuff) {
		foreach($stuff as $weapon_column => $column_value) {
			if($weapon_column == 'type') {
				echo '<option value="'.$column_value.'">'.$column_value.'</option>';
			}
		}
	}
	?>
    </select>
    <input type="hidden" id="wid" value="">
	<br />
    <?php
	echo 'Attack Bonus';
	echo '<br>';
	?>
    <input type="radio" name="atkbonus" value="-5">-5
    <input type="radio" name="atkbonus" value="-4">-4
    <input type="radio" name="atkbonus" value="-3">-3
    <input type="radio" name="atkbonus" value="-2">-2
    <input type="radio" name="atkbonus" value="-1">-1
    <input type="radio" name="atkbonus" value="0" checked>0
    <input type="radio" name="atkbonus" value="+1">+1
    <input type="radio" name="atkbonus" value="+2">+2
    <input type="radio" name="atkbonus" value="+3">+3
    <input type="radio" name="atkbonus" value="+4">+4
    <input type="radio" name="atkbonus" value="+5">+5
    <?php
	echo '<br>';
	echo 'Enemy Type';
	echo '<br>';
		foreach($enemies as $something => $orother) {
		foreach($orother as $enemy_column => $column_value) {
			if($enemy_column == 'name') {
				echo '<input type="radio" name="enemytype" value="'.$column_value.'">'.$column_value;
			}
		}
	}
	?>
	<input type="submit" value="Submit">
	</form>
	


<?php
page_shut();
