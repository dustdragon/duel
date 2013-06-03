<?php

require_once(dirname(__FILE__) . '/lib/php/db.php');


function talk_to_db($prepd_sql) {
	return get_connection($x, $y, $prepd_sql); 
	/*$z = get_connection($x, $y, $prepd_sql); 
	echo '<hr>';
	print_r($z);
	echo '<hr>';
	return $z;*/
}

function die_roll($x) {
    return rand(1,$x);
}

function attack_roll() {
    return rand(1,20);
}

/*function turn() {
	$ret_val = array();
} */

function create_enemy_action() {
    $enemy_act = '';
    $act = rand(0,3);
    switch($act) {
        case(0):
            $enemy_act = 'Leaps out from behind a bush!';
            break;
        case(1):
            $enemy_act = 'Jumps out of a tree and blocks your path!';
            break;
        case(2):
            $enemy_act = 'Stops you as you travel through the woods!';
            break;
        case(3):
            $enemy_act = 'Vaults over the rock he was hiding behind and charges!';
            break;
        default:
            break;
    }
    return $enemy_act;
}

function createenemyprefix($tohit,$totalbp) {

    $ret_enemy_pre = array();
    $enemyprefix = rand(0,10);

    switch ($enemyprefix) {
        case (0) :
            $prefix = 'Wild';
            $tohit = $tohit + 1;
            $totalbp = $totalbp + 1;
            break;
        case (1) :
            $prefix = 'Hungry';
            $tohit = $tohit + 4;
            $totalbp = $totalbp - 2;
            break;
        case (2) :
            $prefix = 'Crazed';
            $tohit = $tohit - 2;
            $totalbp = $totalbp + 4;
            break;
        case (3) :
            $prefix = 'Large';
            $tohit = $tohit + 2;
            $totalbp = $totalbp + 2;
            break;
        case (4) :
            $prefix = 'Smelly';
            $tohit = $tohit + 3;
            $totalbp = $totalbp - 1;
            break;
        case (5) :
            $prefix = 'Angry';
            $tohit = $tohit + 5;
            $totalbp = $totalbp + 5;
            break;
        case (6) :
            $prefix = 'Startled';
            $tohit = $tohit + 1;
            $totalbp = $totalbp - 1;
            break;
        case (7) :
            $prefix = 'Injured';
            $tohit = $tohit - 3;
            $totalbp = $totalbp - 5;
            break;
        case (8) :
            $prefix = 'Small';
            $tohit = $tohit - 2;
            $totalbp = $totalbp - 2;
            break;
        case (9) :
            $prefix = 'Trained';
            $tohit = $tohit + 5;
            $totalbp = $totalbp + 5;
            break;
        case (10) :
            $prefix = 'Hard';
            $tohit = $tohit + 0;
            $totalbp = $totalbp + 10;
            break;
        default:
            break;
    }

    $ret_enemy_pre['pre'] = $prefix;
    $ret_enemy_pre['th'] = $tohit;
    $ret_enemy_pre['tbp'] = $totalbp;

    return $ret_enemy_pre;
}

function adjustenemydamage($enemy, $edt) {

    $x = 0;

    switch ($enemy) {
        case ('Orc' || 'Bandit'):
            $x = die_roll($edt) + 1;
            break;
        case ('Goblin');
            $x = die_roll($edt) - 1;
            break;
        default:
            $x = die_roll($edt);
            break;
    }
    return $x;
}


function fight($a, $b, $h, $dt, $c, $ptv, $e) {

    $ret_val = array();
    $full_attack = ($a + $b);

    switch ($full_attack) {
        case ($full_attack < $h):
            if ($full_attack < ($h/2)) {
                $ret_val['hit'] = 'critmiss';
                $ret_val['dmg'] = 0;
            } else {
                $ret_val['hit'] = 'miss';
                $ret_val['dmg'] = 0;
            }
            break;
        case ($full_attack > ($h-1)):
            if ($a < 19) {
                $ret_val['hit'] = 'hit';
                if ($ptv = true) {
                    $ret_val['dmg'] = die_roll($dt);
                } else {
                    $ret_val['dmg'] = adjustenemydamage($e, $dt);
                }

            } else {
                $ret_val['hit'] = 'crit';
                if ($ptv = true) {
                    $ret_val['dmg'] = die_roll($dt)*$c;
                } else {
                    $ret_val['dmg'] = adjustenemydamage($e, $dt);
                }
            }
            break;
        default:
            break;
    }




    $ret_val['atk'] = $a;

    return $ret_val;
}

function battle_sim($weapon, $bonus, $enemy) {

    $ret = array();
    $playerturnvalue = true;
    $die_type = 0;
    $attack_rate = 0;
    $attack = 0;
    $tohit = 0;
    $fight = array();
    $critmod = 0;
    $weaponbp = 0;
    $playertohit = 14;
    $enemybonus = 0;
    $enemy_attack_rate = 0;
    $enemyfight = array();
    $enemy_die_type = 0;
    $enemycritmod = 0;
    $enemycritthreshold = 0;

    switch($bonus) {
        case ('+1'):
            $bonus = 1;
            break;
        case ('+2'):
            $bonus = 2;
            break;
        case ('+3'):
            $bonus = 3;
            break;
        case ('+4'):
            $bonus = 4;
            break;
        case ('+5'):
            $bonus = 5;
            break;
        case ('-1'):
            $bonus = (0-1);
            break;
        case ('-2'):
            $bonus = (0-2);
            break;
        case ('-3'):
            $bonus = (0-3);
            break;
        case ('-4'):
            $bonus = (0-4);
            break;
        case ('-5'):
            $bonus = (0-5);
            break;
        default:
            $bonus =  0;
            break;
    }
	
	$wep_sql = 'SELECT * FROM weapons WHERE `type` = "'.$weapon.'"';
	$weapon_stats = talk_to_db($wep_sql);
	$die_type = $weapon_stats[0]['die'];
	$weaponbp = $weapon_stats[0]['block_points'];
	$critmod = $weapon_stats[0]['crit_mod'];
	$attack_rate = $weapon_stats[0]['attack_speed'];
	
    //TODO: DUSTY - Add function to vary creature prefix ---FAIL---

    $enemy_prefix = '';
	$en_sql = 'SELECT * FROM enemy WHERE `name` = "'.$enemy.'"';
	$enemy_stats = talk_to_db($en_sql);
	//echo 'eStats: ';
	//print_r($enemy_stats);
	$intro =  'A enemy_prefix '.$enemy.'(enemy_AC, enemy_BP) enemy_action';
	$tohit = $enemy_stats[0]['to_hit'];
	$totalbp = $enemy_stats[0]['block_points'];
	$enemybonus = $enemy_stats[0]['bonus'];
	$enemy_attack_rate = $enemy_stats[0]['attack_speed'];
	$enemy_die_type = $enemy_stats[0]['die'];
	$enemycritmod = $enemy_stats[0]['crit_mod'];
	//echo 'ar: ';
	//print_r($enemy_attack_rate);
    
	/*
	switch($enemy) {
        case ('Kobold'):
            $intro =  'A enemy_prefix Kobold (enemy_AC, enemy_BP) enemy_action';
            $tohit = 10;
            $totalbp = 75;
            $enemybonus = (0-1);
            $enemy_attack_rate = 4;
            $enemy_die_type = 4; //shortsword(small)
            $enemycritmod = 2;
            break;
        case ('Orc'):
            $intro = 'A enemy_prefix Orc (enemy_AC, enemy_BP) enemy_action';
            $tohit = 16;
            $totalbp = 115;
            $enemybonus = 2;
            $enemy_attack_rate = 2;
            $enemy_die_type = 8; //falchion(+1damage)
            $enemycritmod = 4;
            break;
        case ('Bandit'):
            $intro = 'A enemy_prefix Bandit (enemy_AC, enemy_BP) enemy_action';
            $tohit = 14;
            $totalbp = 100;
            $enemybonus = 1;
            $enemy_attack_rate = 3;
            $enemy_die_type = 6; //club(+1damage)
            $enemycritmod = 2;
            break;
        case ('Goblin'):
            $intro = 'A enemy_prefix Goblin (enemy_AC, enemy_BP) enemy_action';
            $tohit = 12;
            $totalbp = 90;
            $enemybonus = 0;
            $enemy_attack_rate = 3;
            $enemy_die_type = 6; //shortsword(-1damage)
            $enemycritmod = 2;
            break;
        default:
            echo '<br>';
            echo 'Please Select an Enemy.';
            break;
    }
	*/

    $enemy_prefix = createenemyprefix($tohit, $totalbp);
    $intro = str_replace('enemy_prefix',$enemy_prefix['pre'],$intro);
    $intro = str_replace('enemy_AC',$enemy_prefix['th'].' AC',$intro);
    $intro = str_replace('enemy_BP',$enemy_prefix['tbp'].' BP',$intro);
    $enemy_action = create_enemy_action();
    $intro = str_replace('enemy_action', $enemy_action, $intro);

    $tohit = $enemy_prefix['th'];
    $totalbp = $enemy_prefix['tbp'];

    $playerturnvalue = true;
    for($i = 0; $i < $attack_rate; $i++) {
        $attack = attack_roll();
        $fight[$i] = fight($attack, $bonus, $tohit, $die_type, $critmod, $playerturnvalue, $enemy);
    }

    $playerturnvalue = false;
	//print_r($enemy_attack_rate);
    for($i = 0; $i < $enemy_attack_rate; $i++) {
        $attack = attack_roll();
        $enemyfight[$i] = fight($attack, $enemybonus, $playertohit, $enemy_die_type, $enemycritmod, $playerturnvalue, $enemy);
		//echo ' enemy fight! <br>';
		//print_r($enemyfight);
    }
    $ret['fight'] = $fight;
    $ret['d'] = $die_type;
    $ret['wbp'] = $weaponbp;
    $ret['intro'] = $intro;
    $ret['tbp'] = $totalbp;
    $ret['enemyfight'] = $enemyfight;
    $ret['enemybonus'] = $enemybonus;

    return $ret;

}

?>