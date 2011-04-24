<?php
/**
*
* @package Board3 Portal v2 - Gallery Module
* @copyright (c) Board3 Group ( www.board3.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
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
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
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
	'PORTAL_GALLERY'=> 'Gallery',

	// ACP
	'ACP_PORTAL_GALLERY_INFO'				=> 'Gallery',
	'ACP_PORTAL_GB_INFO'					=> 'Portal - Gallery block',
	
	'ACP_PORTAL_GALLERY_VERSION'			=> '<strong>Gallery block version: %s</strong>',
	'ACP_PORTAL_GALLERY_SETTINGS'			=> 'Gallery block settings',
	'ACP_PORTAL_GALLERY_SETTINGS_EXPLAIN'	=> 'Here you can change the settings for the gallery block.',
	'ACP_PORTAL_GALLERY_SETTINGS_RIGHT'		=> 'Settings for the small gallery block',
	'ACP_PORTAL_GALLERY_SETTINGS_CENTER'	=> 'Settings for the center gallery block',
	'ACP_PORTAL_GALLERY_SETTINGS_INDEX'		=> 'Settings for the board index',
));
