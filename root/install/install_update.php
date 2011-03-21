<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: install_update.php 61 2009-06-03 15:59:11Z Christian_N $
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
		'module_type'		=> 'update',
		'module_title'		=> 'UPDATE',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 20,
		'module_subs'		=> '',
		'module_stages'		=> array('INTRO', 'REQUIREMENTS', 'UPDATE_DB', 'FINAL'),
		'module_reqs'		=> ''
	);
}

/**
* Installation
* @package install
*/
class install_update extends module
{
	function install_update(&$p_master)
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
					'TITLE'			=> $user->lang['UPDATE_INSTALLATION'],
					'BODY'			=> $user->lang['UPDATE_INSTALLATION_EXPLAIN'],
					'L_SUBMIT'		=> $user->lang['NEXT_STEP'],
					'U_ACTION'		=> $this->p_master->module_url . "?mode=$mode&amp;sub=requirements",
				));
			break;

			case 'requirements':
				$this->check_server_requirements($mode, $sub);
			break;

			case 'update_db':
				$this->update_db_schema($mode, $sub);
			break;

			case 'final':
				set_portal_config('portal_gallery_version', NEWEST_GB_VERSION);
				$cache->purge();

				$template->assign_vars(array(
					'TITLE'		=> $user->lang['INSTALL_CONGRATS'],
					'BODY'		=> sprintf($user->lang['UPDATE_CONGRATS_EXPLAIN'], NEWEST_GB_VERSION),
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
		global $user, $template, $gallery_config, $portal_config, $phpbb_root_path, $phpEx;

		$portal_config = load_portal_config();
		$gallery_config = load_gallery_config();

		$this->page_title = $user->lang['STAGE_REQUIREMENTS'];

		$passed = array('phpbb_gallery' => false, 'board3_portal' => false, 'files' => false,);

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
			$result = '<strong style="color:red">' . sprintf($user->lang['NOT_INSTALLED'], '<a href="' . B3P_DOWNLOAD . '">', '</a>') . '</strong>';
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
			$result = '<strong style="color:red">' . sprintf($user->lang['NOT_INSTALLED'], '<a href="' . PG_DOWNLOAD . '">', '</a>') . '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> sprintf($user->lang['PG_VERSION_REQD'], PG_VERSION),
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));

		// Check whether all old files are deleted
		include($phpbb_root_path . 'install/outdated_files.' . $phpEx);

		umask(0);

		$passed['files'] = true;
		$delete = (isset($_POST['delete'])) ? true : false;
		foreach ($oudated_files as $file)
		{
			if ($delete)
			{
				if (@file_exists($phpbb_root_path . $file))
				{
					// Try to set CHMOD and then delete it
					@chmod($phpbb_root_path . $file, 0777);
					@unlink($phpbb_root_path . $file);
					// Delete failed, tell the user to delete it manually
					if (@file_exists($phpbb_root_path . $file))
					{
						if ($passed['files'])
						{
							$template->assign_block_vars('checks', array(
								'S_LEGEND'			=> true,
								'LEGEND'			=> $user->lang['FILES_OUTDATED'],
								'LEGEND_EXPLAIN'	=> $user->lang['FILES_OUTDATED_EXPLAIN'],
							));
						}
						$template->assign_block_vars('checks', array(
							'TITLE'		=> $file,
							'RESULT'	=> '<strong style="color:red">' . $user->lang['FILE_DELETE_FAIL'] . '</strong>',

							'S_EXPLAIN'	=> false,
							'S_LEGEND'	=> false,
						));
						$passed['files'] = false;
					}
				}
			}
			elseif (@file_exists($phpbb_root_path . $file))
			{
				if ($passed['files'])
				{
					$template->assign_block_vars('checks', array(
						'S_LEGEND'			=> true,
						'LEGEND'			=> $user->lang['FILES_OUTDATED'],
						'LEGEND_EXPLAIN'	=> $user->lang['FILES_OUTDATED_EXPLAIN'],
					));
				}
				$template->assign_block_vars('checks', array(
					'TITLE'		=> $file,
					'RESULT'	=> '<strong style="color:red">' . $user->lang['FILE_STILL_EXISTS'] . '</strong>',

					'S_EXPLAIN'	=> false,
					'S_LEGEND'	=> false,
				));
				$passed['files'] = false;
			}
		}
		if (!$passed['files'])
		{
			$template->assign_block_vars('checks', array(
				'TITLE'			=> '<strong>' . $user->lang['FILES_DELETE_OUTDATED'] . '</strong>',
				'TITLE_EXPLAIN'	=> $user->lang['FILES_DELETE_OUTDATED_EXPLAIN'],
				'RESULT'		=> '<input class="button1" type="submit" id="delete" onclick="this.className = \'button1 disabled\';" name="delete" value="' . $user->lang['FILES_DELETE_OUTDATED'] . '" />',

				'S_EXPLAIN'	=> true,
				'S_LEGEND'	=> false,
			));
		}

		$url = (!in_array(false, $passed)) ? $this->p_master->module_url . "?mode=$mode&amp;sub=update_db" : $this->p_master->module_url . "?mode=$mode&amp;sub=requirements";
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
	* Add some Tables, Columns and Index to the database-schema
	*/
	function update_db_schema($mode, $sub)
	{
		global $db, $user, $template, $portal_config, $table_prefix, $phpbb_root_path, $phpEx, $cache;

		$portal_config = load_portal_config();
		$this->page_title = $user->lang['STAGE_UPDATE_DB'];

		switch ($portal_config['portal_gallery_version'])
		{
			case '1.0.0':
				set_portal_config('portal_gallery', '1');
				set_portal_config('portal_gallery_center', '0');
				set_portal_config('portal_album_id', '');
				set_portal_config('portal_album_id_center', '');
				set_portal_config('portal_images_sort', '0');
				set_portal_config('portal_images_sort_center', '0');
				set_portal_config('portal_images_number', '1');
				set_portal_config('portal_images_number_center', '3');

			case '1.0.1':
				set_portal_config('portal_allow_pers_gallery', '0');
				set_portal_config('portal_allow_pers_gallery_ct', '0');

			case '1.0.2':
				set_portal_config('portal_allow_name', '1');
				set_portal_config('portal_allow_poster', '1');
				set_portal_config('portal_allow_time', '1');
				set_portal_config('portal_allow_views', '1');
				set_portal_config('portal_allow_ratings', '1');
				set_portal_config('portal_allow_comments', '1');
				set_portal_config('portal_allow_album', '1');

			case '1.0.3':
			case '1.1.0':
				//remove the columns
				$old_portal_configs = array('portal_allow_pers_gallery', 'portal_allow_pers_gallery_ct');
				$sql = 'DELETE FROM ' . PORTAL_CONFIG_TABLE . '
					WHERE ' . $db->sql_in_set('config_name', $old_portal_configs);
				$db->sql_query($sql);

			case '1.1.0a':
				$sql = 'UPDATE ' . MODULES_TABLE . " SET
					module_basename = 'portal_gallery'
					WHERE module_langname = 'ACP_PORTAL_GALLERY_INFO'";
				$db->sql_query($sql);

			case '1.2.0':
			case '1.2.1':
			case '1.2.2':
				$sql = 'UPDATE ' . MODULES_TABLE . " SET
					module_auth = 'acl_a_portal_manage'
					WHERE module_langname = 'ACP_PORTAL_GALLERY_INFO'";
				$db->sql_query($sql);

			case '1.2.3':
				//remove the columns
				$old_portal_configs = array('portal_gallery', 'portal_gallery_center', 'portal_album_id', 'portal_album_id_center', 'portal_images_sort', 'portal_images_sort_center', 'portal_images_number', 'portal_images_number_center', 'portal_allow_name', 'portal_allow_poster', 'portal_allow_time', 'portal_allow_views', 'portal_allow_ratings', 'portal_allow_comments', 'portal_allow_album');
				$sql = 'DELETE FROM ' . PORTAL_CONFIG_TABLE . '
					WHERE ' . $db->sql_in_set('config_name', $old_portal_configs);
				$db->sql_query($sql);

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

			case '1.4.0':
			break;
		}

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['STAGE_CREATE_TABLE'],
			'BODY'		=> $user->lang['STAGE_CREATE_TABLE_EXPLAIN'],
			'L_SUBMIT'	=> $user->lang['NEXT_STEP'],
			'S_HIDDEN'	=> '',
			'U_ACTION'	=> $this->p_master->module_url . "?mode=$mode&amp;sub=final",
		));
	}
}

?>