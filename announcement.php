<?php
/*
Plugin Name: Announcements
Plugin URI:   https://tooltips.org
Description: Show announcements in admin area for any logged in users,  if user did not accept announcements, they can not open any menu in back end.
Version: 1.9.3
Author: Tomas Zhu:
Author URI: https://tooltips.org 
Text Domain: wordpress-announcements
License: GPLv3 or later 
*/
/*  Copyright 2016 - 2024 Tomas Zhu
 This program comes with ABSOLUTELY NO WARRANTY;
 https://www.gnu.org/licenses/gpl-3.0.html
 https://www.gnu.org/licenses/quick-guide-gplv3.html
 */
if (!defined('ABSPATH'))
{
	exit;
}

define( 'TOMAS_ANNOUNCEMENTS_VERSION', '1.9.3' );
define('TOMAS_AN_FOLDER', dirname(plugin_basename(__FILE__)));
define('TOMAS_AN_FILE_PATH', dirname(__FILE__));
define('TOMAS_AN_DIR_NAME', basename(TOMAS_AN_FILE_PATH));

$m_hadInstall = get_option('announcementInstalled');
if (empty($m_hadInstall))
{
	//echo "install your database!";
	require_once("announcementinstall.php");
	update_option('announcementInstalled','1.9.3');
}

add_action( 'wp_enqueue_scripts', 'tomas_announcementLoaderScripts' );
function tomas_announcementLoaderScripts()
{
	wp_register_script( 'frontendannouncement', plugin_dir_url( __FILE__ ).'asset/js/scroll-up-bar.js', array('jquery'));
	wp_enqueue_script( 'frontendannouncement' );
}


function tomas_announcementMenu()
{
	global $menu, $submenu,$wpdb,$table_prefix;
	global  $wp_roles,$current_user,$user_ID;
	get_currentuserinfo();
	
	if (!isset($current_user->id))
	{
  		die("You should not see this page!");
	}	
	
	
	if ($current_user->id == 1)
	{
		return;
	}
	
	$table_announce = $table_prefix . "announcement";
	$table_user =  $table_prefix . "announceuser";

	$m_canNotVistNow = false;
	$m_haveAnnounce = "SELECT * FROM `".$table_announce."` WHERE `valid` = 'YES' AND `withoutAgreement` = 'NO'";
	$current_data = $wpdb->get_results($m_haveAnnounce,ARRAY_A);
	if (empty($current_data))
	{
		return;
	}
	else
	{
		{
			foreach ($current_data as $current_data)
			{
				$m_record_id   = $current_data['record_id'];
				$m_userInfoSql = "SELECT * FROM `".$table_user."` WHERE  `had_submit` = 'YES' AND  `info_id` = '".$m_record_id."' AND `user_id` = '". $current_user->id ."' LIMIT 1";
				$m_resultUserInfo = $wpdb->get_row($m_userInfoSql,ARRAY_A);
				if (empty($m_resultUserInfo))
				{
					$m_canNotVistNow = true;
					break;
				}
				
			}
		}
	}
	
	if ( true == $m_canNotVistNow )
	{
		if (!(is_admin()))
		{
			return;
		}
		
		if  (stripos($_SERVER['REQUEST_URI'], '/wp-admin/admin.php?page=Announcements') === false)
		{
			$an_url = get_option('siteurl') . '/wp-admin/admin.php?page=Announcements';
			$an_url =	wp_nonce_url($an_url);
			wp_safe_redirect($an_url);
			exit();			
		}
		else 
		{
			
		}
		
		
	}

}


function tomas_announcementInsertMenu()
{
	//add_menu_page(__('Announcements', 'Announcements'), __('Announcements', 'Announcements'), 1, 'Announcements', 'tomas_announcementMenu');
	//add_submenu_page('Announcements', __('Announcements','Announcement'), __('Announcements','Announcement'), 1, 'Announcements', 'tomas_announcementMenuFrontEnd');

	add_menu_page(__('Announcements', 'Announcements'), __('Announcements', 'Announcements'), 'edit_posts', 'Announcements', 'tomas_announcementMenu');
	add_submenu_page('Announcements', __('Announcements','Announcement'), __('Announcements','Announcement'), 'edit_posts', 'Announcements', 'tomas_announcementMenuFrontEnd');

	if (current_user_can("administrator"))
	{
		//add_submenu_page('Announcements', __('Announcement Settings','Announcement'), __('Announcement Settings','Announcement'), 10, 'Announcement-Settings', 'tomas_announcementMenuAdmin');
		//add_submenu_page('Announcements', __('Site Frontend Announcements','Announcement'), __('Site Frontend Announcements','Announcement'), 10, 'Announcements-Site-Frontend', 'tomas_webFrontendAnnouncementSettings');
		add_submenu_page('Announcements', __('Announcement Settings','Announcement'), __('Announcement Settings','Announcement'), 'manage_options', 'Announcement-Settings', 'tomas_announcementMenuAdmin');
		add_submenu_page('Announcements', __('Site Frontend Announcements','Announcement'), __('Site Frontend Announcements','Announcement'), 'manage_options', 'Announcements-Site-Frontend', 'tomas_webFrontendAnnouncementSettings');		
	}

}


function tomas_webFrontendAnnouncementSettings()
{
	require_once ('frontendannouncementsettings.php');
}


function tomas_announcementMenuAdmin()
{
	require_once ('serverend.php');
}

function tomas_announcementMenuFrontEnd()
{
	require_once("frontend.php");
}

require_once("frontendannouncementload.php");

add_action( 'admin_menu', 'tomas_announcementInsertMenu');
add_action( 'admin_menu', 'tomas_announcementMenu'); //!!

function tomas_webFrontendAnnouncementMessage($p_message)
{

	echo "<div id='message' class='updated fade' style='line-height: 30px;margin-left: 0px;margin-top:10px; margin-bottom:10px;'>";

	echo $p_message;

	echo "</div>";

}
?>
