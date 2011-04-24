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
	'RANDOM_IMAGE'	=> 'Zufälliges Bild',
	'RECENT_IMAGE'	=> 'Neueste Bild',
	'PORTAL_GALLERY'=> 'Gallery',
	
	// ACP
	'ACP_PORTAL_GALLERY_INFO'				=> 'Galerie',
	'ACP_PORTAL_GB_INFO'					=> 'Portal - Gallery Block',
	
	'ACP_PORTAL_GALLERY_VERSION'			=> '<strong>Galerie Block Version: %s</strong>',
	'ACP_PORTAL_GALLERY_SETTINGS'			=> 'Einstellungen für den Galerie Block',
	'ACP_PORTAL_GALLERY_SETTINGS_EXPLAIN'	=> 'Hier kannst du die Einstellungen für den Galerie Block ändern.',
	'ACP_PORTAL_GALLERY_SETTINGS_RIGHT'		=> 'Einstellungen für den kleinen Block',
	'ACP_PORTAL_GALLERY_SETTINGS_CENTER'	=> 'Einstellungen für den mittleren Block',
	'ACP_PORTAL_GALLERY_SETTINGS_INDEX'		=> 'Einstellungen für die Foren-Übersicht',
	)
);

?>