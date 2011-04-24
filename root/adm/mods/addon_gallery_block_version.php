<?php

/**
*
* @package B3P Addon - Gallery block
* @version $Id: addon_gallery_block_version.php 67 2009-09-30 14:28:10Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

class addon_gallery_block_version
{
	function version()
	{
		global $config, $phpbb_root_path, $phpEx;

		return array(
			'author'	=> 'Christian_N, Marc Aleander', 
			'title'		=> 'board3 Portal Add-ON: Gallery Block',
			'tag'		=> 'addon_gallery_block',
			'version'	=> $config['portal_gallery_version'],
			'file'		=> array('www.m-a-styles.de', 'updatecheck', 'gallery_block.xml'),
		);
	}
}

?>