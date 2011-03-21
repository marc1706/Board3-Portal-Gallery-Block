<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: outdated_files.php 58 2009-06-03 11:56:11Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @installer based on: phpBB Gallery by nickvergessen, www.flying-bits.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
if (!defined('IN_INSTALL'))
{
	exit;
}

$oudated_files = array(
	'language/de/mods/gallery_block.php',
	'language/de/mods/lang_install_gb.php',
	'language/en/mods/gallery_block.php',
	'language/en/mods/lang_install_gb.php',
	'portal/block/album.php',
	'portal/includes/functions_album.php',
	'portal/includes/functions_album_center.php',
	'styles/prosilver/template/portal/block/album.html',
	'styles/prosilver/template/portal/block/album_center.html',
	'styles/prosilver/template/portal/block/album_small.html',
	'styles/subsilver2/template/portal/block/album.html',
	'styles/subsilver2/template/portal/block/album_center.html',
	'styles/subsilver2/template/portal/block/album_small.html',
);

?>