<?php
/*
Plugin Name: Commission Junction Link Shortcode
Plugin URI: http://shinraholdings.com/plugins/commission-junction-link-shortcode
Description: Customize and insert Commission Junction links using a simple shortcode.
Version: 1.0.1
Author: bitacre
Author URI: http://shinraholdings.com

Shortcode Format: cj url="link-url" img="tracking-img-url"]link text[/cj]
	
License: GPLv2 
	Copyright 2012 Shinra Web Holdings (http://shinraholdings.com)

*/

function set_plugin_meta_cj_link_shortcode($links, $file) { // define additional plugin meta links
	$plugin = plugin_basename(__FILE__); // '/cj-link-shortcode/cj-link-shortcode.php' by default
    if ($file == $plugin) { // if called for THIS plugin then:
		$newlinks=array('<a href="http://shinraholdings.com/plugins/cj-link-shortcode/help">Help Page</a>',); // array of links to add
		return array_merge( $links, $newlinks ); // merge new links into existing $links
	}
return $links; // return the $links (merged or otherwise)
}

function trim_url($untrimmed) { // sanatize url inputs
	$trimmed = trim(str_replace("http://www.","",$untrimmed));
	return $trimmed;
}

function cj_link( $atts, $content=NULL ) {
	extract( shortcode_atts( array( 'url'=>NULL, 'img'=>NULL ), $atts ) );
	//error checking
	$errormsg = '<!--CJ link shortcode failed. The correct syntax is [cj url="link-url" img="tracking-img-url"]link text[/cj] . Reason for failure: ';
	$iserror = 0;
	if(is_null($url)) { $iserror=1; $errormsg = $errormsg . "No url specified. "; }
	if(is_null($img)) { $iserror=1; $errormsg = $errormsg . "No tracking image specified. "; }
	if(is_null($content)) { $iserror=1; $errormsg = $errormsg . "No link text specified. ";}
	if($iserror) { 
		$errormsg = $errormsg . "-->"; 
		return $errormsg; 
	}
	
	// sanatize input
	$cleanurl = trim_url($url);
	$cleanimg = trim_url($img);
	
	// create link code
	$link_code='<a href="http://www.' . $cleanurl . '" target="_top">' . $content . '</a><img src="http://www.' . $cleanimg . '" width="1" height="1" border="0" />';
	return $link_code;
}

add_filter( 'plugin_row_meta', 'set_plugin_meta_cj_link_shortcode', 10, 2 ); // add meta links to plugin's section on 'plugins' page (10=priority, 2=num of args)
add_shortcode( 'cj', 'cj_link' );
?>