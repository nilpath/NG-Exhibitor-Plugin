<?php
/**
 * @package NG_Exhibitor
 * @version 1.0
 */
/*
Plugin Name: NG Exhibitor
Plugin URI: http://www.thsnaringsliv.se
Author: Christoffer Åhrling
Description: Plugin for adding Exhibitors and searching for them.
Version: 1.0
Author URI: http://www.thsnaringsliv.se
*/
require_once("ngexhibitor.php");

add_action("init", "NGEventInit"); // Initierar pluginen
function NGEventInit() {
	global $ngexhibitors;
	$ngexhibitors = new NGExhibitor();
}
