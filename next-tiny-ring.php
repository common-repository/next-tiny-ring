<?php
/*
* Plugin Name: Next Tiny Ring
* Plugin URI: https://nxt-web.com/plugins/next-tiny-ring/
* Description: Next Tiny Ring allows you to propose an advertising solution through display of banner ads shared on a network of several websites. Choose the banner ads to be displayed and the websites where to add them. Configure the style of the banners and the ads to be displayed. Manage your banner ads (create new banners, modify or delete). Usefull to increase your SEO between your websites creating entering links.
* Author: F.Leroux
* Text Domain: next-tiny-ring
* Domain Path: /languages
* Version: 3.1
* Author URI: https://nxt-web.com/
*/

/*
Copyright 2023 F.Leroux
*/

if (!defined('ABSPATH')) exit;

global $wpdb;

if (!defined('NTRNG_VERSION'))
   { define('NTRNG_VERSION','3.1');
   }
if (!defined('NTRNG_TYPE'))
   { define('NTRNG_TYPE','Free');
   }

function ntrng_PluginActivation()
{ update_option('ntrngCurrentVersion',NTRNG_VERSION);
  update_option('ntrngCurrentType',NTRNG_TYPE);
    
  return NTRNG_VERSION;
}
register_activation_hook(__FILE__,'ntrng_PluginActivation');

/*
function ntrng_InstallDB()
{	global $wpdb;
	global $opt_VersionDB;

	$TableName = $wpdb->prefix . 'ntrng';
	$charset_collate = $wpdb->get_charset_collate();

	//$sql = "CREATE TABLE $TableName (
	$sql = "CREATE TABLE IF NOT EXISTS $TableName (
	     id INT(6) UNSIGNED AUTO_INCREMENT,
		   web VARCHAR(127) NOT NULL,
		   link VARCHAR(127) NOT NULL,
		   title VARCHAR(63) NOT NULL,
		   description VARCHAR(255) NOT NULL,
		   PRIMARY KEY  (id)
	     ) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
        
	add_option('optVersionDB',$opt_VersionDB);
}*/
//register_activation_hook(__FILE__,'ntrng_InstallDB');

require_once plugin_dir_path(__FILE__) . 'includes/ntrng-functions.php';
