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

/**
 * A filter to find if a page contains the [exhibitor] code, if yes, then display the exhibitor page.
 */

add_filter('the_content', 'NGExhibitorReplaceCode');  

/**
 * A function to list the exhibitors in alphabetical order
 */
function NGExhibitorReplaceCode($content){
	// Find the [exhibitor] code
	$match = preg_match_all('/\[exhibitor\]/i', $content, $matches);  
	if($matches){
		$content = "";
		// Get all exhibitors
		$exhibitors = get_posts(array('numberposts' => 0, 'post_type' => 'ngexhibitor'));
		$alphabetic_array = array();
		// Group them by letter in an array
		foreach($exhibitors as $exhibitor){
			if(!$alphabetic_array[$exhibitor->post_title[0]]) $alphabetic_array[$exhibitor->post_title[0]] = array();
			array_push($alphabetic_array[$exhibitor->post_title[0]], $exhibitor);
		}
	// Add the swedish letters to the alphabet
	$alphabet = array_merge( range('A', 'Z'), array('Å', 'Ä', 'Ö') );
	// Calculate how many rows per column we want
	$rows_per_column = ceil(count($alphabet)/4);
	$content .= '<table><tr>';
	foreach($alphabet as $index => $letter) {
		if($index%$rows_per_column == 0) $content .= '<td>';
		$content .= '<h2>'.$letter.'</h2>';
		if($alphabetic_array[$letter]){
			$content .= '<ul>';
			foreach($alphabetic_array[$letter] as $single_alphabetical_post)
				$content .= '<li>'.$single_alphabetical_post->post_title.'</li>';
			$content .= '</ul>';
		}
		if(($index+1)%$rows_per_column==0) $content .= '</td>';
	}
	$content .= '</tr></table>';
	}
	return $content;
}