<?php
/**
*
* @package Board3 addon - Gallery block
* @version $Id: install_main.php 60 2009-06-03 15:50:12Z Christian_N $
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

if (!empty($setmodules))
{
	$module[] = array(
		'module_type'		=> 'install',
		'module_title'		=> 'OVERVIEW',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 0,
		'module_subs'		=> array('INTRO', 'UNINSTALL'),
		'module_stages'		=> '',
		'module_reqs'		=> ''
	);
}

/**
* Main Tab - Installation
* @package install
*/
class install_main extends module
{
	function install_main(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($mode, $sub)
	{
		global $user, $template, $phpbb_root_path, $cache, $phpEx;

		switch ($sub)
		{
			case 'intro':
				$this->page_title = $user->lang['SUB_INTRO'];

				$template->assign_vars(array(
					'TITLE'			=> $user->lang['UNINSTALL_INTRO'],
					'BODY'			=> $user->lang['UNINSTALL_INTRO_BODY'],
					'L_SUBMIT'		=> $user->lang['NEXT_STEP'],
					'U_ACTION'		=> $this->p_master->module_url . "?mode=$mode&amp;sub=uninstall",
				));
			break;

			case 'uninstall':
				$this->uninstall($mode, $sub);
			break;
		}

		$this->tpl_name = 'install_install';
	}
	
	/**
	* Load the contents of the schema into the database and then alter it based on what has been input during the installation
	*/
	function uninstall($mode, $sub)
	{
		global $db, $user, $template, $phpbb_root_path, $phpEx, $cache, $table_prefix;

		$this->page_title = $user->lang['STAGE_UNINSTALL'];
		
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
		
		//remove the columns
		$old_portal_configs = array('portal_gallery', 'portal_gallery_center', 'portal_album_id', 'portal_album_id_center', 'portal_images_sort', 'portal_images_sort_center', 'portal_images_number', 'portal_images_number_center', 'portal_allow_pers_gallery', 'portal_allow_pers_gallery_ct', 'portal_allow_name', 'portal_allow_poster', 'portal_allow_time', 'portal_allow_views', 'portal_allow_ratings', 'portal_allow_comments', 'portal_allow_album', 'portal_pg_small_mode', 'portal_pg_small_rows', 'portal_pg_small_display', 'portal_pg_small_pgalleries', 'portal_pg_center_mode', 'portal_pg_center_display', 'portal_pg_center_rows', 'portal_pg_center_columns', 'portal_pg_center_crows', 'portal_pg_center_contests', 'portal_pg_center_pgalleries', 'portal_pg_center_comments', 'portal_pg_index_mode', 'portal_pg_index_display', 'portal_pg_index_rows', 'portal_pg_index_columns', 'portal_pg_index_crows', 'portal_pg_index_contests', 'portal_pg_index_pgalleries', 'portal_pg_index_comments', 'portal_gallery_version');
		$sql = 'DELETE FROM ' . PORTAL_CONFIG_TABLE . '
			WHERE ' . $db->sql_in_set('config_name', $old_portal_configs);
		$db->sql_query($sql);
	
		//remove the module
		$sql = 'SELECT module_id FROM ' . MODULES_TABLE . "
			WHERE module_langname = 'ACP_PORTAL_GALLERY_INFO'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			remove_module($row['module_id'], 'acp');
		}
		$db->sql_freeresult($result);
		$cache->purge();
				
		$template->assign_vars(array(
			'TITLE'		=> $user->lang['UNINSTALL_CONGRATS'],
			'BODY'		=> $user->lang['UNINSTALL_CONGRATS_EXPLAIN'],
			'L_SUBMIT'	=> $user->lang['GOTO_PORTAL'],
			'U_ACTION'	=> append_sid($phpbb_root_path . 'portal.' . $phpEx),
		));
	}
}

?>