<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: install_install.php 61 2009-06-03 15:59:11Z Christian_N $
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
		'module_title'		=> 'INSTALL',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 10,
		'module_subs'		=> '',
		'module_stages'		=> array('INTRO', 'REQUIREMENTS', 'CREATE_TABLE', 'ADVANCED', 'FINAL'),
		'module_reqs'		=> ''
	);
}

/**
* Installation
* @package install
*/
class install_install extends module
{
	function install_install(&$p_master)
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
					'TITLE'			=> $user->lang['INSTALL_INTRO'],
					'BODY'			=> $user->lang['INSTALL_INTRO_BODY'],
					'L_SUBMIT'		=> $user->lang['NEXT_STEP'],
					'U_ACTION'		=> $this->p_master->module_url . "?mode=$mode&amp;sub=requirements",
				));
			break;

			case 'requirements':
				$this->check_server_requirements($mode, $sub);
			break;

			case 'create_table':
				$this->load_schema($mode, $sub);
			break;

			case 'advanced':
				$this->obtain_advanced_settings($mode, $sub);
			break;

			case 'final':
				set_portal_config('portal_gallery_version', NEWEST_GB_VERSION, true);
				$cache->purge();

				$template->assign_vars(array(
					'TITLE'		=> $user->lang['INSTALL_CONGRATS'],
					'BODY'		=> sprintf($user->lang['INSTALL_CONGRATS_EXPLAIN'], NEWEST_GB_VERSION),
					'L_SUBMIT'	=> $user->lang['GOTO_PORTAL'],
					'U_ACTION'	=> append_sid($phpbb_root_path . 'portal.' . $phpEx),
				));
			break;
		}

		$this->tpl_name = 'install_install';
	}

	/**
	* Checks that the server we are installing on meets the requirements for running phpBB
	*/
	function check_server_requirements($mode, $sub)
	{
		global $user, $config, $gallery_config, $portal_config, $template, $phpbb_root_path, $phpEx;

		$portal_config = load_portal_config();
		$gallery_config = load_gallery_config();
		
		$this->page_title = $user->lang['STAGE_REQUIREMENTS'];

		$passed = array('phpbb_gallery' => false, 'board3_portal' => false,);

		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> true,
			'LEGEND'			=> $user->lang['VERSION_CHECK'],
			'LEGEND_EXPLAIN'	=> $user->lang['VERSION_REQD_EXPLAIN'],
		));

		// Board3 Portal Version-Check
		if (isset($portal_config['portal_version']))
		{
			if (version_compare($portal_config['portal_version'], B3P_VERSION) < 0)
			{
				$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
			}
			else
			{
				$passed['board3_portal'] = true;
				$result = '<strong style="color:green">' . $user->lang['YES'] . '</strong>';
			}
		}
		else
		{
			$result = '<strong style="color:red">' . $user->lang['B3P_NOT_INSTALLED'] . '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> sprintf($user->lang['B3P_VERSION_REQD'], B3P_VERSION),
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));
	
		// phpBB Gallery Version-Check
		if (isset($gallery_config['phpbb_gallery_version']))
		{
			if (version_compare($gallery_config['phpbb_gallery_version'], PG_VERSION) < 0)
			{
				$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
			}
			else
			{
				$passed['phpbb_gallery'] = true;
				$result = '<strong style="color:green">' . $user->lang['YES'] . '</strong>';
			}
		}
		else
		{
			$result = '<strong style="color:red">' . sprintf($user->lang['PG_NOT_INSTALLED'], '<a href="' . PG_DOWNLOAD . '">', '</a>') . '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> sprintf($user->lang['PG_VERSION_REQD'], PG_VERSION),
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));

		$url = (!in_array(false, $passed)) ? $this->p_master->module_url . "?mode=$mode&amp;sub=create_table" : $this->p_master->module_url . "?mode=$mode&amp;sub=requirements";
		$submit = (!in_array(false, $passed)) ? $user->lang['INSTALL_START'] : $user->lang['INSTALL_TEST'];

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['REQUIREMENTS_TITLE'],
			'BODY'		=> '',
			'L_SUBMIT'	=> $submit,
			'S_HIDDEN'	=> '',
			'U_ACTION'	=> $url,
		));
	}
	
	/**
	* Load the contents of the schema into the database and then alter it based on what has been input during the installation
	*/
	function load_schema($mode, $sub)
	{
		global $user, $template, $phpbb_root_path, $phpEx, $cache;

		$this->page_title = $user->lang['STAGE_CREATE_TABLE'];

		// Set default config
		set_portal_config('portal_pg_small_mode', '1');
		set_portal_config('portal_pg_small_rows', '1');
		set_portal_config('portal_pg_small_display', '127');
		set_portal_config('portal_pg_small_pgalleries', '0');
		set_portal_config('portal_pg_center_mode', '0');
		set_portal_config('portal_pg_center_display', '127');
		set_portal_config('portal_pg_center_rows', '1');
		set_portal_config('portal_pg_center_columns', '3');
		set_portal_config('portal_pg_center_crows', '5');
		set_portal_config('portal_pg_center_contests', '0');
		set_portal_config('portal_pg_center_pgalleries', '0');
		set_portal_config('portal_pg_center_comments', '1');
		set_portal_config('portal_pg_index_mode', '0');
		set_portal_config('portal_pg_index_display', '127');
		set_portal_config('portal_pg_index_rows', '1');
		set_portal_config('portal_pg_index_columns', '4');
		set_portal_config('portal_pg_index_crows', '5');
		set_portal_config('portal_pg_index_contests', '0');
		set_portal_config('portal_pg_index_pgalleries', '0');
		set_portal_config('portal_pg_index_comments', '1');


		$template->assign_vars(array(
			'TITLE'		=> $user->lang['STAGE_CREATE_TABLE'],
			'BODY'		=> $user->lang['STAGE_CREATE_TABLE_EXPLAIN'],
			'L_SUBMIT'	=> $user->lang['NEXT_STEP'],
			'S_HIDDEN'	=> '',
			'U_ACTION'	=> $this->p_master->module_url . "?mode=$mode&amp;sub=advanced",
		));
	}

	/**
	* Provide an opportunity to customise some advanced settings during the install
	* in case it is necessary for them to be set to access later
	*/
	function obtain_advanced_settings($mode, $sub)
	{
		global $user, $template, $phpEx, $db;

		//$modules = new acp_modules();
		$sql = 'SELECT module_id FROM ' . MODULES_TABLE . " WHERE module_langname = 'ACP_PORTAL_INFO'";
		$result = $db->sql_query_limit($sql, 1);
		$check_module_id = $db->sql_fetchrow($result);
		
		$acp_portal_gallery = array('module_basename'	=> 'portal_gallery',	'module_enabled' => 1,	'module_display' => 1,	'parent_id' => $check_module_id['module_id'], 'module_class' => 'acp',	'module_langname'=> 'ACP_PORTAL_GALLERY_INFO', 'module_mode' => 'gallery', 'module_auth' => '');
		add_module($acp_portal_gallery);

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['STAGE_ADVANCED'],
			'BODY'		=> $user->lang['STAGE_ADVANCED_SUCCESSFUL'],
			'L_SUBMIT'	=> $user->lang['NEXT_STEP'],
			'S_HIDDEN'	=> '',
			'U_ACTION'	=> $this->p_master->module_url . "?mode=$mode&amp;sub=final",
		));
	}
}

?>