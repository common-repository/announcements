<?php
if (!defined('ABSPATH'))
{
	exit;
}

$m_contentFrontendAnnouncement = get_option('contentFrontendAnnouncement');

function tomas_frontendAnnouncementTop()
{
	$m_contentFrontendAnnouncement = get_option('contentFrontendAnnouncement');
	echo '<div id="topbar" style="position: absolute; top: 0; left: 0; width: 100%; z-index:999999; background: #ccc;">';
	echo "<div style='text-align:center'>";
	echo $m_contentFrontendAnnouncement;
	echo '</div>';
	echo '</div>';
}

if (!(empty($m_contentFrontendAnnouncement)))
{
	add_action('wp_head', 'tomas_frontendAnnouncementTop');
}

//add_action('wp_footer', 'tomas_frontendAnnouncementLoading');

function tomas_frontendAnnouncementLoading()
{
?>
<<script type="text/javascript">
jQuery(document).ready( function($) {
	jQuery('#topbar').scrollupbar();
});
<?php 
}
?>