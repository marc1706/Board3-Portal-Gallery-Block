<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: install_functions.php 58 2009-06-03 11:56:11Z Christian_N $
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

/*
* Advanced: Add/update a portal-config value
*/
function set_portal_config($column, $value, $update = false)
{
	global $db;

	$sql = 'SELECT * FROM ' . PORTAL_CONFIG_TABLE . " WHERE config_name = '$column'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if (!$row)
	{
		$sql_ary = array(
			'config_name'				=> $column,
			'config_value'				=> $value,
		);
		$db->sql_query('INSERT INTO ' . PORTAL_CONFIG_TABLE . $db->sql_build_array('INSERT', $sql_ary));
	}
	else
	{
		$sql_ary = array(
			'config_name'				=> $column,
			'config_value'				=> $value,
		);
		$db->sql_query('UPDATE ' . PORTAL_CONFIG_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE config_name = '$column'");
	}
}

/*
* Adds a module to the phpbb_modules-table
* @param	array	$array	Exp:	array('module_basename' => '',	'module_enabled' => 1,	'module_display' => 1,	'parent_id' => $choosen_acp_module,	'module_class' => 'acp',	'module_langname'=> 'PHPBB_GALLERY',	'module_mode' => '',	'module_auth' => '')
*/
function add_module($array)
{
	global $user;
	$modules = new acp_modules();
	$failed = $modules->update_module_data($array, true);
}

/*
* Removes a module of the phpbb_modules-table
*	Note: Be sure that the module exists, otherwise it may give an error message
*/
function remove_module($module_id, $module_class)
{
	global $user;
	$modules = new acp_modules();
	$modules->module_class = $module_class;
	$failed = $modules->delete_module($module_id);
}


/*
* Advanced: Load gallery-config values
*/
function load_gallery_config()
{
	global $db;

	$gallery_config = array();

	$sql = 'SELECT * FROM ' . GALLERY_CONFIG_TABLE;
	$result = $db->sql_query($sql);
	while( $row = $db->sql_fetchrow($result) )
	{
		$gallery_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);

	return $gallery_config;
}

/*
* Advanced: Load portal-config values
*/
function load_portal_config()
{
	global $db;

	$portal_config = array();

	$sql = 'SELECT * FROM ' . PORTAL_CONFIG_TABLE;
	$result = $db->sql_query($sql);
	while( $row = $db->sql_fetchrow($result) )
	{
		$portal_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);

	return $portal_config;
}

/*
* Create a back-link
*	Note: just like phpbb3's adm_back_link
* @param	string	$u_action	back-link-url
*/
function adm_back_link($u_action)
{
	global $user;
	return '<br /><br /><a href="' . $u_action . '">&laquo; ' . $user->lang['BACK_TO_PREV'] . '</a>';
}


?>