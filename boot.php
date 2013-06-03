<?php

require_once(dirname(__FILE__).'/functions.php');

function start_html($title, $heading) {
	echo "<!doctype html>\n";
	echo '<html>';
	echo '<head>';
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
	//echo '<script language="javascript" type="text/javascript" src="lib/js/jquery-1.10.1.min.js"></script>';
	//echo '<script language="javascript" type="text/javascript" src="lib/js/functions.js"></script>';
	echo '<title>'.$title.'</title>';
	echo '<link rel="stylesheet" type="text/css" href="css/styles.css">';
	echo '</head><body>';
	echo '<h1>'.$heading.'</h1>';
}

function page_shut() {
	echo '</body></html>';
}

function do_battle($w, $b, $e) {
	return battle_sim($w, $b, $e);
}

function get_stuff_from_db($ps) {
	return talk_to_db($ps);
}
