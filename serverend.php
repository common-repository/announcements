<?php
if (!defined('ABSPATH'))
{
	exit;
}

	global $table_prefix, $wpdb;
	$table_announce = $table_prefix . "announcement";

	if ((!(empty($_POST['submitAdminSetting']))) || (!(empty($_POST['submitUpdateSetting']))) || (!(empty($_POST['hiddenAdminSetting']))) || (!(empty($_POST['formUpdateSetting'])))  || (!(empty($_POST['hiddenUpdateing']))))
	{
		check_admin_referer( 'tomas_insert_messagebox' );
		
		$m_selectInfoType = '';
		$m_textareaMessage = '';
		$m_inputDays = 0;
		$m_selectCountDown = '';
		$m_inputReminderText = '';
		$m_inputAdminEmail = '';
		$m_inputCheckboxLabel = '';
		$m_showCountdown = "NO";
		$m_inputDate = date("Y-m-d H:i:s");
		if(!(empty($_POST['selectInfoType'])))
		{
			$m_selectInfoType = sanitize_text_field($_POST['selectInfoType']);
		}
		else 
		{
			$m_selectInfoType = "NO";
		}
		
		if(!(empty($_POST['textareaMessage'])))
		{
			$m_textareaMessage = sanitize_text_field($_POST['textareaMessage']);
		}
		
		if(!(empty($_POST['texteditMessage'])))
		{
			$m_textareaMessage = sanitize_text_field($_POST['texteditMessage']);
		}		


		if(!(empty($_POST['inputCheckboxLabel'])))
		{
			$m_inputCheckboxLabel = sanitize_text_field($_POST['inputCheckboxLabel']);
		}
		if(!(empty($_POST['inputTitle'])))
		{
			$m_inputTitle = sanitize_text_field($_POST['inputTitle']);
		}		
		if(!(empty($_POST['agreementRequired'])))
		{
			$m_agreementRequired = sanitize_text_field($_POST['agreementRequired']);
		}
		else 
		{
			//$m_agreementRequired = "NO";
			$m_agreementRequired = "YES";
		}
		
		if(!(empty($_POST['withoutAgreement'])))
		{
			$m_withoutAgreement = sanitize_text_field($_POST['withoutAgreement']);
		}
		else 
		{
			$m_withoutAgreement = "NO";
		}		
		
		$table_name = $table_prefix . "announcement";
		if ('submit' == $_POST['hiddenSaveSubmit'])
		{
			//$m_checkHadThere = "SELECT `record_id` FROM `".$table_name."` WHERE `textTitle` = '".$m_inputTitle."' LIMIT 1";
			$m_checkHadThere = $wpdb->prepare( "SELECT record_id FROM $table_name WHERE textTitle = %s LIMIT 1",$m_inputTitle);
			$m_checkResult = $wpdb->get_var($m_checkHadThere);
			if (!(empty($m_checkResult)))
			{
				/*
				$m_mysql = "UPDATE `$table_name` SET  `agreementRequired` = '".$wpdb->escape($m_agreementRequired)
				."' , `withoutAgreement`='".$wpdb->escape($m_withoutAgreement)
				."', `showCountdown`='".$wpdb->escape($m_showCountdown)
				."', `textTitle`='".$wpdb->escape($m_inputTitle)
				."', `textareaMessage`='".$wpdb->escape($m_textareaMessage)
				."', `inputDays`='".$wpdb->escape($m_inputDays)
				."', `selectCountDown`='".$wpdb->escape($m_selectCountDown)
				."', `inputReminderText`='".$wpdb->escape($m_inputReminderText)
				."', `inputAdminEmail`='".$wpdb->escape($m_inputAdminEmail)
				."', `inputCheckboxLabel`='".$wpdb->escape($m_inputCheckboxLabel)
				."', `inputDate`='".$wpdb->escape($m_inputDate)
				."', `valid`='YES' WHERE `record_id`='".$m_checkResult."'";
				*/
				
				
				$m_mysql = $wpdb->prepare( "UPDATE $table_name SET  agreementRequired = %s , withoutAgreement= %s, showCountdown=%s, textTitle=%s, textareaMessage=%s
				, inputDays=%s, selectCountDown=%s, inputReminderText=%s, inputAdminEmail=%s, inputCheckboxLabel=%s, inputDate=%s, valid='YES'  
						WHERE record_id=%d"
				,$wpdb->escape($m_agreementRequired),$wpdb->escape($m_withoutAgreement),$wpdb->escape($m_showCountdown)
				,$wpdb->escape($m_inputTitle),$wpdb->escape($m_textareaMessage),$wpdb->escape($m_inputDays),$wpdb->escape($m_selectCountDown)
				,$wpdb->escape($m_inputReminderText),$wpdb->escape($m_inputAdminEmail),$wpdb->escape($m_inputCheckboxLabel),$wpdb->escape($m_inputDate),$m_checkResult
								);
				
				$wpdb->query($m_mysql);
				echo "<div style='background: #fff5ee;width: 80%;line-height: 25px;margin: 10px 10px 30px 10px;border:#dcdcdc 1px solid ;padding:10px 10px 10px 10px'>";						
				echo "<B>Your mssage has updated and will shown to your users!</B>";
				echo "<br />";
				echo "</div>";
			}
			else 
			{
				/*
				$m_mysql = "INSERT INTO `$table_name` (`agreementRequired`,`withoutAgreement`,`showCountdown`, `textTitle`, `textareaMessage`, `inputDays`, `selectCountDown`, `inputReminderText`,`inputAdminEmail`,`inputCheckboxLabel`,`inputDate`,`valid`) VALUES ('".
				$wpdb->escape($m_agreementRequired).
				"', '".$wpdb->escape($m_withoutAgreement).
				"', '".$wpdb->escape($m_showCountdown).
				"', '".$wpdb->escape($m_inputTitle).
				"', '".$wpdb->escape($m_textareaMessage).
				"', '".$wpdb->escape($m_inputDays)."', '".$wpdb->escape($m_selectCountDown).
				"','".$wpdb->escape($m_inputReminderText)."','".$wpdb->escape($m_inputAdminEmail)."','".
				$wpdb->escape($m_inputCheckboxLabel)."','".$wpdb->escape($m_inputDate)."','YES')";
				*/

				$m_mysql = $wpdb->prepare("INSERT INTO $table_name (agreementRequired,withoutAgreement,showCountdown, textTitle, textareaMessage, inputDays, selectCountDown, inputReminderText,inputAdminEmail,inputCheckboxLabel,inputDate,valid) 
						VALUES (%s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,'YES')",
						'YES',$wpdb->escape($m_withoutAgreement),$wpdb->escape($m_showCountdown),
						$wpdb->escape($m_inputTitle),$wpdb->escape($m_textareaMessage),$wpdb->escape($m_inputDays),$wpdb->escape($m_selectCountDown)
						,$wpdb->escape($m_inputReminderText),$wpdb->escape($m_inputAdminEmail),$wpdb->escape($m_inputCheckboxLabel),$wpdb->escape($m_inputDate)
								);
				$wpdb->query($m_mysql);
				echo "<div style='background: #fff5ee;width: 80%;line-height: 25px;margin: 10px 10px 10px 10px;border:#dcdcdc 1px solid ;padding:10px 10px 10px 10px'>";						
				echo "<B>Your new message has been updated and will shown to your users!</B>";
				echo "</div>";
				
			}
		}

		
		
		if ((!(empty($_POST['submitUpdateSetting'])))  || (!(empty($_POST['hiddenUpdateing']))))
		{
			if(!(empty($_POST['hiddenRecordId'])))
			{
				$m_hiddenRecordId = sanitize_text_field($_POST['hiddenRecordId']);
			}

			if(!(empty($_POST['selectValid'])))
			{
				$m_valid = sanitize_text_field($_POST['selectValid']);
			}
			//$m_mysql = "UPDATE `$table_name` SET  `selectInfoType` = '$m_selectInfoType' , `textareaMessage`='$m_textareaMessage', `inputDays`='$m_inputDays', `selectCountDown`='$m_selectCountDown', `inputReminderText`='$m_inputReminderText',`inputAdminEmail`='$m_inputAdminEmail',`inputCheckboxLabel`='$m_inputCheckboxLabel',`inputDate`='$m_inputDate',`valid`='$m_valid' WHERE `record_id`='$m_hiddenRecordId'";
			$m_mysql = $wpdb->prepare("UPDATE $table_name SET  selectInfoType = %s , textareaMessage=%s, inputDays=%s, selectCountDown=%s, inputReminderText=%s,inputAdminEmail=%s,inputCheckboxLabel=%s,inputDate=%s,valid=%s WHERE record_id=%d"
					,$m_selectInfoType,$m_textareaMessage,$m_inputDays,$m_selectCountDown,$m_inputReminderText,$m_inputAdminEmail,$m_inputCheckboxLabel,$m_inputDate,$m_valid,$m_hiddenRecordId
					);
			
			$wpdb->query($m_mysql);
		}
	}
	
	//1.9.1
	if (isset($_POST['submitOneToDelete']))
	{
	    if ('Delete it' == $_POST['submitOneToDelete'])
	    {
	        check_admin_referer( 'tomas_insert_messagebox' );
	        $table_name = $table_prefix . "announcement";
	        if (!(empty($_POST['selectNewOrUpdate'])))
	        {
	            //$m_mysql = "DELETE FROM `$table_name` WHERE `record_id` = ".sanitize_text_field($_POST['selectNewOrUpdate']);
	            $m_mysql = $wpdb->prepare("DELETE FROM $table_name WHERE record_id = %d",sanitize_text_field($_POST['selectNewOrUpdate']));
	            
	            $wpdb->query($m_mysql);
	        }
	    }
	}

?>
<div style="margin-left:30px;margin-top:15px;">
</div>

<div id='box' style='width: 80%;    overflow: auto;    margin: 16px 0;   padding: 23px 10px 30px;    border: 1px solid #e5e5e5;    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);    box-shadow: 0 1px 1px rgba(0,0,0,.04);    background: #fff;    font-size: 13px;    line-height: 2.1em;'  >
	<?php
	if (!(empty($_POST['submitOneToUpdate'])))
	{
		check_admin_referer( 'tomas_insert_messagebox' );
		$now_editID =  sanitize_text_field($_POST['selectNewOrUpdate']);
		$table_announce = $table_announce = $table_prefix . "announcement";
		//$m_sqlNowRecord = "SELECT * FROM `".$table_announce."` WHERE `record_id` = '".$now_editID."' LIMIT 1";
		 
		$m_sqlNowRecord  = $wpdb->prepare("SELECT * FROM $table_announce WHERE record_id = %d LIMIT 1",$now_editID);
		
		$m_editResult = $wpdb->get_row($m_sqlNowRecord,ARRAY_A);
	?>		
		<form id="formAdminSetting" action="" method="POST">
		<div>
			<label style="font-size:16px;"><B>Announcement Message Editor</B></label>
			<hr />
			<b>Title:</b><br />
			<input type="text" id="inputTitle" name="inputTitle" size="80" value="<?php echo esc_attr($m_editResult['textTitle']); ?>">
		</div>
		<div>
		<div>
			<b>Message:</b>
		</div>
		<div>
<textarea id="textareaMessage" name="textareaMessage" cols="80" rows="10" >
<?php echo $m_editResult['textareaMessage']; ?>
</textarea>
<div style='display:none'>
Yes<input type="radio" name="agreementRequired" id="agreementRequired" value="YES">
</div>
		</div>
		<br />
		<table width="88%">
		<tr>
		<td align="left">
				<b>Access without agreement:</b>
		<?php 
				if ('YES' == $m_editResult['withoutAgreement']) 
				{		
		?>
					Yes<input type="radio" name="withoutAgreement" id="withoutAgreement" value="YES" checked>
					No<input type="radio" name="withoutAgreement" id="withoutAgreement" value="NO">
				<?php
				}
				else 
				{
				?>
					Yes<input type="radio" name="withoutAgreement" id="withoutAgreement" value="YES">
					No<input type="radio" name="withoutAgreement" id="withoutAgreement" value="NO" checked>				
				<?php
				}
				?>					
		</td>
		</tr>
		<tr >
		<td align="left">
				<b>Checkbox text:</b><br />
				<input id="inputCheckboxLabel" name="inputCheckboxLabel" size="60" value="<?php echo esc_attr($m_editResult['inputCheckboxLabel']) ?>">		
		</td>
		</tr>
		</table>

		<br />
		<?php wp_nonce_field('tomas_insert_messagebox'); ?>
		<input type ="hidden" id="hiddenAdminSetting" name="hiddenAdminSetting" value="YES">
		<input type ="hidden" id="hiddenSaveSubmit" name="hiddenSaveSubmit" value="submit">
		<input type="submit" id="submitAdminSetting"  class='button button-primary' name="submitAdminSetting" value="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	</form>		
<?php
	}
	else 
	{
?>		
	
	<form id="formAdminSetting" action="" method="POST">
	<div>
		<label style="font-size:16px;"><B>Announcement Message Editor</B></label>
		<hr />
		<b>Title:</b><br />
		<input type="text" id="inputTitle" name="inputTitle" size="80">
	</div>
	<div>
		<b>Message:</b><br />
<textarea id="textareaMessage" name="textareaMessage" cols="80" rows="10" >
</textarea>
<div style='display: none;'>
Yes<input type="radio" name="agreementRequired" id="agreementRequired" value="YES">
</div>
		<br />
		<br />
		<table width="88%">
		<tr>

		<td align="left">
				<b>Access without agreement:</b>

				Yes<input type="radio" name="withoutAgreement" id="withoutAgreement" value="YES">
				No<input type="radio" name="withoutAgreement" id="withoutAgreement" value="NO">
		</td>
		</tr>
		<tr >
		
		<td align="left">
				<b>Checkbox text:</b><br />
				<input id="inputCheckboxLabel" name="inputCheckboxLabel" size="60">		
		</td>
		</tr>
		</table>

		<br />
		<?php wp_nonce_field('tomas_insert_messagebox'); ?>
		<input type ="hidden" id="hiddenAdminSetting" name="hiddenAdminSetting" value="YES">
		<input type ="hidden" id="hiddenSaveSubmit" name="hiddenSaveSubmit" value="submit">
		<input type="submit" id="submitAdminSetting"  class='button button-primary' name="submitAdminSetting" value="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	</form>
	<?php
	}
	?>
</div>

<div id='box' style='width: 80%;    overflow: auto; margin:16px 0; padding: 23px 10px 30px;    border: 1px solid #e5e5e5;    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);    box-shadow: 0 1px 1px rgba(0,0,0,.04);    background: #fff;    font-size: 13px;    line-height: 2.1em;' >
	<div>
		<label style="font-size:16px;"><B>Edit / Delete Messages</B></label>
		<hr />	
		<div style="height:14px;line-height:14px;padding:6px;margin:6px;font-size:14px;">
		<form id="formNewOrUpdate" method="POST" action="">
		
		<?php
			$table_announce = $table_prefix . "announcement";
			
			$m_haveAnnounce = "SELECT * FROM `".$table_announce."`";
			//$m_haveAnnounce = $wpdb->prepare("SELECT * FROM $table_announce");
			$m_resultAnnounce = $wpdb->get_results($m_haveAnnounce,ARRAY_A);
			if (empty($m_resultAnnounce))
			{
				echo "You have no any Announce/Message in system yet!<br />";
			}
			else
			{
				echo "Choose: &nbsp;&nbsp;&nbsp;&nbsp; <select id='selectNewOrUpdate' name='selectNewOrUpdate' style='margin-top:5px;'>";
				foreach ($m_resultAnnounce as $m_resultAnnounce)
				{
					echo "<option value='".esc_attr($m_resultAnnounce['record_id'])."' >".stripslashes($m_resultAnnounce['textTitle']). "</option>";
				}
				echo "</select>";
			?>				
			<?php wp_nonce_field('tomas_insert_messagebox'); ?>
				&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp; 
				<input type="submit" id="submitOneToUpdate" class='button button-primary' name="submitOneToUpdate" value="Edit it" style="font-size:11px;">
				&nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp;
				<input type="submit" id="submitOneToDelete" class='button button-primary'  name="submitOneToDelete" value="Delete it" style="font-size:11px;">
				<br />
		<?php		
			}
		?>
		
		</form>
		</div>
	</div>

<?php
			echo "</div>";
?>