<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: gallery.php 54 2009-05-18 12:50:40Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB') || (!defined('IN_PORTAL')))
{
	exit;
}

/**
* Recent images & comments and random images
*
* borrowed from phpBB Gallery (SVN 1150)
* @author: nickvergessen
* @function: recent_gallery_images
*/
$gallery_root_path = GALLERY_ROOT_PATH;
include($phpbb_root_path . $gallery_root_path . 'includes/functions_recent.' . $phpEx);
$ints = array(
	'rows'		=> $portal_config['portal_pg_center_rows'],
	'columns'	=> $portal_config['portal_pg_center_columns'],
	'comments'	=> $portal_config['portal_pg_center_crows'],
	'contests'	=> $portal_config['portal_pg_center_contests'],
);
/**
* int		array	including all relevent numbers for rows, columns and stuff like that,
* display	int		sum of the options which should be displayed, see gallery/includes/constants.php "// Display-options for RRC-Feature" for values
* modes		int		sum of the modes which should be displayed, see gallery/includes/constants.php "// Mode-options for RRC-Feature" for values
* collapse	bool	collapse comments
* include_pgalleries	bool	include personal albums
* mode_id	string	'user' or 'album' to only display images of a certain user or album
* id		int		user_id for user profile or album_id for view of recent and random images
*/
if ($portal_config['portal_pg_center_mode'] != '!all')
{
	recent_gallery_images($ints, $portal_config['portal_pg_center_display'], $portal_config['portal_pg_center_mode'], $portal_config['portal_pg_center_comments'], $portal_config['portal_pg_center_pgalleries']);
}

include($phpbb_root_path . 'portal/includes/functions_gallery.' . $phpEx);
$ints = array(
	'small_rows'	=> $portal_config['portal_pg_small_rows'],
	'small_columns'	=> 1,
);
if ($portal_config['portal_pg_small_mode'] != '!all')
{
	small_gallery_images($ints, $portal_config['portal_pg_small_display'], $portal_config['portal_pg_small_mode'], $portal_config['portal_pg_small_pgalleries']);
}


?>