<?php
if (!defined('ABSPATH'))
{
	exit;
}

	global $table_prefix, $wpdb,$wp_roles,$current_user,$user_ID;
	//#fafafa
	get_currentuserinfo();
	$table_announce = $table_prefix . "announcement";
	$table_user =  $table_prefix . "announceuser";
	   
	if(empty($current_user->id))
	{
		wp_die("You should not saw this page!");
	}
	else 
	{
		if (!(empty($_POST['submitMessage'])))
		{
			check_admin_referer( 'tomas_show_messagebox' );
			$m_adminArea = get_option('siteurl') . '/wp-admin/';
			
			if (!(empty($_POST['checkThisMessage'])))
			{
				$table_name = $table_prefix . "announceuser";
				$m_hiddenRecordId = sanitize_text_field($_POST['hiddenRecordId']);
				$m_hiddenInfoType = sanitize_text_field($_POST['hiddenInfoType']); // !!! no user for ,delete !
				//$m_updateSql = "UPDATE  `".$table_name."` SET `had_submit` = 'YES' WHERE `user_id` = '".$current_user->id."' and `info_id` = '".$m_hiddenRecordId."'";
				//$wpdb->query($m_updateSql);
				$tomas_update_frontend_sql_result = $wpdb->query( $wpdb->prepare( "UPDATE  $table_name SET had_submit = 'YES' WHERE user_id = %d and info_id = %d", $current_user->id,$m_hiddenRecordId ) );
				
				$m_adminAreaChecked = get_option('siteurl') . '/wp-admin/';
				echo "<div style='background: #fafafa;width: 80%;line-height: 25px;margin: 10px 10px 30px 10px;border:#dcdcdc 1px solid ;padding:10px'>";
				//1.8.9
				echo "<B>Thanks,This message will not show again,click <a href='".esc_attr($m_adminAreaChecked)."'>here</a> will go to admin area</B>";
				//echo "<B>Thanks,This message will not show again,click <a href='".$m_adminAreaChecked."'>here</a> will go to admin area</B>";
				echo "<br />";
				echo "</div>";				
			}
		}
		
		$m_haveAnnounce = "SELECT * FROM `".$table_announce."` WHERE `valid` = 'YES'";
		//$m_haveAnnounce = $wpdb->prepare( "SELECT * FROM $table_announce WHERE valid = 'YES'");
		$m_resultAnnounce = $wpdb->get_results($m_haveAnnounce,ARRAY_A);
		if (empty($m_resultAnnounce))
		{
			
		}
		else 
		{
			$m_frontSql = "SELECT * FROM `".$table_announce."` WHERE `agreementRequired` = 'YES' and `valid` = 'YES'";
			//$m_frontSql =  $wpdb->prepare( "SELECT * FROM $table_announce WHERE agreementRequired = 'YES' and valid = 'YES'" ) ;
			$current_data  = $wpdb->get_results($m_frontSql,ARRAY_A);
			if (!(empty($current_data)))
			{
				foreach ($current_data as $current_data)
				{
					$m_record_id   = stripslashes($current_data['record_id']);
					
					$m_frontWithoutAgreement = stripslashes($current_data['withoutAgreement']); 
					
					$m_frontTextTitle = stripslashes($current_data['textTitle']);
					$m_infoType = "agreement";
					$m_textareaMessage   = stripslashes($current_data['textareaMessage']);	 
					$m_inputDays   = stripslashes($current_data['inputDays']);		
					$m_selectCountDown   = stripslashes($current_data['selectCountDown']); 
					$m_inputReminderText   = stripslashes($current_data['inputReminderText']);  
					$m_inputAdminEmail   = stripslashes($current_data['inputAdminEmail']);	 
					$m_inputCheckboxLabel   = stripslashes($current_data['inputCheckboxLabel']);
					$m_inputDate   = stripslashes($current_data['inputDate']);	
					$m_TodayDate = date('Y-m-d H:i:s');					
					//$m_userInfoSql = "SELECT * FROM `".$table_user."` WHERE `info_id` = '".$m_record_id."' AND `user_id` = '". $current_user->id ."' LIMIT 1";
					$m_userInfoSql =  $wpdb->prepare( "SELECT * FROM $table_user WHERE info_id = %d AND user_id = %d LIMIT 1", $m_record_id,$current_user->id);

					$m_resultUserInfo = $wpdb->get_row($m_userInfoSql,ARRAY_A);

					if (empty($m_resultUserInfo))
					{
						//$m_mysql = "INSERT INTO `$table_user` (`user_id`, `info_id`, `had_submit`, `inputDate`) VALUES ('".$current_user->id."', '$m_record_id', 'NO', '$m_TodayDate')";
						$m_mysql = $wpdb->prepare( "INSERT INTO $table_user (user_id, info_id, had_submit, inputDate) VALUES (%s, %d, 'NO', %s)",  $current_user->id,$m_record_id,$m_TodayDate ); 
						$wpdb->query($m_mysql);
						$m_userStartDay = $m_TodayDate;
						$m_countdownLeft = 0;
						tomas_shownAnnounceNow($m_infoType,$m_frontTextTitle,$m_record_id,$m_selectCountDown,$m_inputCheckboxLabel,$m_textareaMessage,$m_inputReminderText,$m_inputDays,$m_countdownLeft,$m_inputAdminEmail);						
					}
					else 
					{
						$m_hadSumbit = $m_resultUserInfo['had_submit'];
						$m_userStartDay = $m_resultUserInfo['inputDate'];									
						$m_TodayDate = strtotime(date('Y-m-d'));
						$array1 = explode(" ",$m_userStartDay);
						$array_date = $array1[0];
						$m_userStartDay = strtotime($array_date);

						$m_countdownLeft = round(($m_TodayDate - $m_userStartDay)/3600/24);
						
						if ('NO' == $m_hadSumbit)
						{
							tomas_shownAnnounceNow($m_infoType,$m_frontTextTitle,$m_record_id,$m_selectCountDown,$m_inputCheckboxLabel,$m_textareaMessage,$m_inputReminderText,$m_inputDays,$m_countdownLeft,$m_inputAdminEmail);
						}
					}
				}
			}

			$table_name = $table_prefix . "announcement";
			$m_frontSql = "SELECT * FROM `".$table_name."` WHERE `agreementRequired` = 'NO' and `valid` = 'YES'";
			//$m_frontSql =  $wpdb->prepare( "SELECT * FROM $table_name WHERE agreementRequired = 'NO' and valid = 'YES'" ) ;
			
			$current_data  = $wpdb->get_results($m_frontSql,ARRAY_A);
			if (!(empty($current_data)))
			{
				foreach ($current_data as $current_data)
				{
					$m_infoType = "message";
					$m_frontWithoutAgreement = stripslashes($current_data['withoutAgreement']); 
					
					$m_frontTextTitle = stripslashes($current_data['textTitle']);
										
					$m_record_id   = stripslashes($current_data['record_id']);	  
					$m_textareaMessage   = stripslashes($current_data['textareaMessage']);
					$m_inputDays   = stripslashes($current_data['inputDays']);				  	
					$m_selectCountDown   = stripslashes($current_data['selectCountDown']); 
					$m_inputReminderText   = stripslashes($current_data['inputReminderText']);  
					$m_inputAdminEmail   = stripslashes($current_data['inputAdminEmail']);	  
					$m_inputCheckboxLabel   = stripslashes($current_data['inputCheckboxLabel']);
					$m_inputDate   = stripslashes($current_data['inputDate']);	
					$m_TodayDate = date('Y-m-d H:i:s');
					$m_countdownLeft = round(($m_TodayDate - $m_inputDate)/3600/24);
					//$m_userInfoSql = "SELECT * FROM `".$table_user."` WHERE `info_id` = '".$m_record_id."' AND `user_id` = '". $current_user->id ."' LIMIT 1";
					$m_userInfoSql =  $wpdb->prepare( "SELECT * FROM $table_user WHERE info_id = %d AND user_id = %d LIMIT 1", $m_record_id,$current_user->id ) ;
					$m_resultUserInfo = $wpdb->get_row($m_userInfoSql,ARRAY_A);
					
					if (empty($m_resultUserInfo))
					{
						//$m_mysql = "INSERT INTO `$table_user` (`user_id`, `info_id`, `had_submit`, `inputDate`) VALUES ('".$current_user->id."', '$m_record_id', 'NO', '$m_TodayDate')";
						$m_mysql = $wpdb->prepare("INSERT INTO $table_user (user_id, info_id, had_submit, inputDate) VALUES (%d, %d, 'NO', %d)",$current_user->id,$m_record_id,$m_TodayDate);

						$m_userStartDay = $m_TodayDate;
						$m_countdownLeft = 0;
						tomas_shownMessageNow($m_infoType,$m_frontTextTitle,$m_record_id,$m_selectCountDown,$m_inputCheckboxLabel,$m_textareaMessage,$m_inputReminderText,$m_inputDays,$m_countdownLeft,$m_inputAdminEmail);
					
					}
					else 
					{
						$m_hadSumbit = $m_resultUserInfo['had_submit'];
						$m_userStartDay = $m_resultUserInfo['inputDate'];
						$m_countdownLeft = round(($m_TodayDate - $m_userStartDay)/3600/24);
						if ('NO' == $m_hadSumbit)
						{
							tomas_shownMessageNow($m_infoType,$m_frontTextTitle,$m_record_id,$m_selectCountDown,$m_inputCheckboxLabel,$m_textareaMessage,$m_inputReminderText,$m_inputDays,$m_countdownLeft,$m_inputAdminEmail);
						}
					}
				}
			}
			
		}
	}


	
function tomas_shownAnnounceNow($m_infoType,$m_frontTextTitle,$m_record_id,$m_selectCountDown,$m_inputCheckboxLabel,$m_textareaMessage,$m_inputReminderText,$m_inputDays,$m_countdownLeft,$m_inputAdminEmail)
{
	global $table_prefix, $wpdb,$wp_roles,$current_user,$user_ID;
	get_currentuserinfo();	
	echo "<div style='background: #fafafa;width: 80%;line-height: 25px;margin: 10px 10px 30px 10px;border:#dcdcdc 1px solid ;padding:10px'>";		
		echo "<H2>$m_frontTextTitle</H2>";
		echo "$m_textareaMessage";
		echo "<div  style='margin-top:20px;'>";
			echo "<form id='formMessageBox' name='formMessageBox' method='POST' action=''>";
				wp_nonce_field('tomas_show_messagebox');
				echo "<input type='checkbox' id='checkThisMessage' name='checkThisMessage' ><B>$m_inputCheckboxLabel</B><br /><br />";
				echo "<input type='hidden' id='hiddenRecordId' name='hiddenRecordId' value='$m_record_id'>";
				echo "<input type='hidden' id='hiddenInfoType' name='hiddenInfoType' value='$m_infoType'>";
				echo "<input type='submit' value = 'Submit' id='submitMessage' name='submitMessage' class='button button-primary'>";
			echo "</form>";
		echo "</div>";
	echo "</div>";
}

function tomas_shownMessageNow($m_infoType,$m_frontTextTitle,$m_record_id,$m_selectCountDown,$m_inputCheckboxLabel,$m_textareaMessage,$m_inputReminderText,$m_inputDays,$m_countdownLeft,$m_inputAdminEmail)
{
	echo "<div style='background: #fafafa;width: 80%;line-height: 25px;margin: 10px 10px 30px 10px;border:#dcdcdc 1px solid ;padding:10px'>";
		echo "<H2>$m_frontTextTitle</H2>";
		echo "$m_textareaMessage";
		echo "<div  style='margin-top:20px;'>";

				echo "<form id='formMessageBox' name='formMessageBox' method='POST' action=''>";
				wp_nonce_field('tomas_show_messagebox');
				echo "<input type='checkbox' id='checkThisMessage' name='checkThisMessage' ><B>$m_inputCheckboxLabel</B><br /><br />";
				echo "<input type='hidden' id='hiddenRecordId' name='hiddenRecordId' value='$m_record_id'>";
				echo "<input type='hidden' id='hiddenInfoType' name='hiddenInfoType' value='$m_infoType'>";
				echo "<input type='submit' value='Submit' id='submitMessage' name='submitMessage'  class='button button-primary'>";
			echo "</form>";
		echo "</div>";
	echo "</div>";
}
?>