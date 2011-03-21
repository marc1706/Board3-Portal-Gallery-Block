<?php

/**
*
* @package B3P Addon - Gallery block
* @version $Id: info_acp_b3p_gallery.php 57 2009-05-28 15:27:31Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
	
// DEVELOPERS PLEASE NOTE
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine


$lang = array_merge($lang, array(
	// MAIN
	'RANDOM_IMAGE'	=> 'Random image',
	'RECENT_IMAGE' 	=> 'Recent image',

	// ACP
	'ACP_PORTAL_GALLERY_INFO'				=> 'Gallery',
	'ACP_PORTAL_GB_INFO'					=> 'Portal - Gallery block',
	
	'ACP_PORTAL_GALLERY_VERSION'			=> '<strong>Gallery block version: %s</strong>',
	'ACP_PORTAL_GALLERY_SETTINGS'			=> 'Gallery block settings',
	'ACP_PORTAL_GALLERY_SETTINGS_EXPLAIN'	=> 'Here you can change the settings for the gallery block.',
	'ACP_PORTAL_GALLERY_SETTINGS_RIGHT'		=> 'Settings for the small gallery block',
	'ACP_PORTAL_GALLERY_SETTINGS_CENTER'	=> 'Settings for the center gallery block',
	'ACP_PORTAL_GALLERY_SETTINGS_INDEX'		=> 'Settings for the board index',
	)
);

?>