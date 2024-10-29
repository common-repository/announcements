<?php
if (!defined('ABSPATH'))
{
	exit;
}


function tomas_webFrontendAnnouncementSettingsPanel()
{
	global $wpdb;

	$m_contentFrontendAnnouncement = get_option('contentFrontendAnnouncement');

	if (isset($_POST['announcementsubmitnew']))
	{
		check_admin_referer( 'tomas_announcement_setting_nonce' );
		if (isset($_POST['contentFrontendAnnouncement']))
		{
			$m_contentFrontendAnnouncement = sanitize_textarea_field($_POST['contentFrontendAnnouncement']);
		}
		else
		{

		}

		update_option('contentFrontendAnnouncement',$m_contentFrontendAnnouncement);

		$announcementMessageString =  __( 'Your changes has been saved.', 'wordpress-announcements' );
		tomas_webFrontendAnnouncementMessage($announcementMessageString);
	}
	echo "<br />";

	$saved_register_page_url = get_option('contentFrontendAnnouncement');
	?>

		<div style='margin:10px 5px;'>
		<div style='float:left;margin-right:10px;'>

		<img src='<?php echo plugins_url('/asset/images/new.png', __FILE__);  ?>' style='width:30px;height:30px;'>
		
		</div> 
		<div style='padding-top:5px; font-size:22px;'>Frontend Announcement Settings:</div>
		</div>
		<div style='clear:both'></div>		
			<div class="wrap">
				<div id="dashboard-widgets-wrap">
			    	<div id="dashboard-widgets" class="metabox-holder">
						<div id="post-body">
							<div id="dashboard-widgets-main-content">
								<div class="postbox-container" style="width:90%;">
									<div class="postbox">
										<h3 class='hndle' style='padding: 20px; !important'>
											<span>
											<?php 
												echo  __( 'Frontend Announcement Panel:', 'wordpress-announcements' );
											?>
											</span>
										</h3>
								
										<div class="inside" style='padding-left:10px;'>
											<form id="tomas_webFrontendAnnouncementForm" name="tomas_webFrontendAnnouncementForm" action="" method="POST">
											<?php 
											wp_nonce_field('tomas_announcement_setting_nonce');
											?>
											<table id="tomas_announcement_table" width="100%">
											<tr style="margin-top:30px;">
											<td width="30%" style="padding: 20px;" valign="top">
											<?php 
												echo  __( 'Frontend Announcement:', 'wordpress-announcements' );
											?>
											</td>
											<td width="70%" style="padding: 20px;">
											<?php 
											$urlsarray = get_option('contentFrontendAnnouncement'); 
											?>
											<textarea name="contentFrontendAnnouncement" id="contentFrontendAnnouncement" cols="70" rows="10" style="width:500px;"><?php echo sanitize_textarea_field($urlsarray); ?></textarea>
											<p><font color="Gray"><i><?php echo  __( 'Announcement will show at the top of the site pages', 'wordpress-announcements' ); ?></i></p>					
											</td>
											</tr>
											</table>
											<br />
											<input type="submit" id="announcementsubmitnew" name="announcementsubmitnew" value=" Submit " style="margin:1px 20px;">
											</form>
											<br />
										</div>
									</div>
								</div>
							</div>
						</div>
		    		</div>
				</div>
		</div>
		<div style="clear:both"></div>
		<br />
		<?php
}

tomas_webFrontendAnnouncementSettingsPanel();
?>