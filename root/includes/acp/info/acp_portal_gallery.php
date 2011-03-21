<?php

/**
*
* @package B3P Addon - Gallery block
* @version $Id: acp_portal_gallery.php 59 2009-06-03 12:05:35Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/
class acp_portal_gallery_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_portal_gallery',
			'title'		=> 'ACP_PORTAL_GB_INFO',
			'version'	=> '1.2.4',
			'modes'		=> array(
				'gallery'		=> array('title' => 'ACP_PORTAL_GALLERY_INFO', 'auth' => 'acl_a_portal_manage', 'cat' => array('ACP_PORTAL_INFO')),
			),
		);
	}
}

?>