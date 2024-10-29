<?php
if (!defined('ABSPATH'))
{
	exit;
}
require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
global $table_prefix, $wpdb;

function announceInstall()
{	
	global $table_prefix, $wpdb;
	$charset_collate = '';
	if($wpdb->supports_collation()) 
	{
		if(!empty($wpdb->charset)) 
		{
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)) 
		{
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}

	$table_name = $table_prefix . "announcement";
	$wpdb->query("DROP TABLE `".$table_name."`");
	if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") !== $table_name)
	{	
		$createRrRatingSql = "CREATE TABLE `".$table_name."` (".
			"`record_id` INT(11) NOT NULL auto_increment,".
			"`agreementRequired` VARCHAR(255) NOT NULL default '',".
			"`withoutAgreement` VARCHAR(255) NOT NULL default '',".
			"`showCountdown` VARCHAR(255) NOT NULL default '',".
			"`textTitle` VARCHAR(255) NOT NULL default '',".
			"`textareaMessage` TEXT NOT NULL default '',".
			"`inputDays` INT(10) NOT NULL  default '0',".
			"`selectCountDown` VARCHAR(15) NOT NULL  default '',".
			"`inputReminderText` VARCHAR(255) NOT NULL  default '',".
			"`inputAdminEmail` VARCHAR(255) NOT NULL default '',".
			"`inputCheckboxLabel` VARCHAR(255) NOT NULL default '',".
			"`inputDate` datetime NOT NULL default '0000-00-00 00:00:00',".
			"`valid`  VARCHAR(255) NOT NULL  default '',".
			"PRIMARY KEY (record_id)) $charset_collate;";
	}

	dbDelta($createRrRatingSql);
	
	$table_name = $table_prefix . "announceuser";
	$wpdb->query("DROP TABLE `".$table_name."`");
	if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") !== $table_name)
	{	
		$createRrRatingSql = "CREATE TABLE `".$table_name."` (".
			"record_id INT(11) NOT NULL auto_increment,".
			"user_id VARCHAR(255) NOT NULL  default '',".
			"info_id VARCHAR(255) NOT NULL  default '',".
			"had_submit VARCHAR(255) NOT NULL  default '',".
			"inputDays INT(10) NOT NULL  default '0',".
			"selectCountDown VARCHAR(15) NOT NULL  default '',".
			"inputReminderText VARCHAR(255) NOT NULL  default '',".
			"inputAdminEmail VARCHAR(255) NOT NULL default '',".
			"inputCheckboxLabel VARCHAR(255) NOT NULL default '',".
			"inputDate datetime NOT NULL default '0000-00-00 00:00:00',".
			"PRIMARY KEY (record_id)) $charset_collate;";
	}
	dbDelta($createRrRatingSql);	
}
announceInstall();
?>