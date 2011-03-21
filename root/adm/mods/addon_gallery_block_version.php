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
		global $portal_config, $phpbb_root_path, $phpEx;
		if (!function_exists('obtain_portal_config'))
		{
			include($phpbb_root_path . 'portal/includes/functions.' . $phpEx);
		}
		$portal_config = obtain_portal_config();

		return array(
			'author'	=> 'Christian_N, Marc Aleander', 
			'title'		=> 'board3 Portal Add-ON: Gallery Block',
			'tag'		=> 'addon_gallery_block',
			'version'	=> $portal_config['portal_gallery_version'],
			'file'		=> array('www.m-a-styles.de', 'versioncheck', 'gallery_block.xml'),
		);
	}
}

?>