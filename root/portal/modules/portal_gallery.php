<?php
/**
*
* @package Board3 Portal v2 - Gallery Block
* @copyright (c) Board3 Group ( www.board3.de )
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

/**
* @package Gallery Block
*/
class portal_gallery_module
{
	/**
	* Allowed columns: Just sum up your options (Exp: left + right = 10)
	* top		1
	* left		2
	* center	4
	* right		8
	* bottom	16
	*/
	var $columns = 31;

	/**
	* Default modulename
	*/
	var $name = 'PORTAL_GALLERY';

	/**
	* Default module-image:
	* file must be in "{T_THEME_PATH}/images/portal/"
	*/
	var $image_src = 'portal_gallery.png';

	/**
	* module-language file
	* file must be in "language/{$user->lang}/mods/portal/"
	*/
	var $language = 'portal_gallery_module';
	
	/**
	* custom acp template
	* file must be in "adm/style/portal/"
	*/
	var $custom_acp_tpl = '';

	function get_template_center($module_id)
	{
		global $config, $template;
		
		$gallery_root_path = GALLERY_ROOT_PATH;

		if(!function_exists('recent_gallery_images'))
		{
			include($phpbb_root_path . $gallery_root_path . 'includes/functions_recent.' . $phpEx);
		}
		
		$ints = array(
			'rows'		=> $config['board3_gallery_center_rows_' . $module_id],
			'columns'	=> $config['board3_gallery_center_columns_' . $module_id],
			'comments'	=> $config['board3_gallery_center_crows_' . $module_id],
			'contests'	=> $config['board3_gallery_center_contests_' . $module_id],
		);
		
		if ($config['board3_gallery_center_mode_' . $module_id] != '!all') // @todo: check if that is correct
		{
			recent_gallery_images($ints, $config['board3_gallery_center_display_' . $module_id], $config['board3_gallery_center_mode_' . $module_id], $config['board3_gallery_center_comments_' . $module_id], $config['board3_gallery_center_pgalleries_' . $module_id]);
		}

		return 'gallery_center.html';
	}

	function get_template_side($module_id)
	{
		global $config, $template;

		$gallery_root_path = GALLERY_ROOT_PATH;

		if(!function_exists('recent_gallery_images'))
		{
			include($phpbb_root_path . $gallery_root_path . 'includes/functions_recent.' . $phpEx);
		}
		
		$ints = array(
			'small_rows'	=> $config['board3_gallery_small_rows_' . $module_id],
			'small_columns'	=> 1,
		);
		if ($config['board3_gallery_small_mode_' . $module_id] != '!all')
		{
			$this->small_gallery_images($ints, $config['board3_gallery_small_display_' . $module_id], $config['board3_gallery_small_mode_' . $module_id], $config['board3_gallery_small_pgalleries_' . $module_id]);
		}

		return 'modulename_side.html';
	}

	function get_template_acp($module_id)
	{
		return array(
			'title'	=> 'ACP_CONFIG_MODULENAME',
			'vars'	=> array(
				'legend1'								=> 'ACP_MODULENAME_CONFIGLEGEND',
				'board3_configname_' . $module_id	=> array('lang' => 'MODULENAME_CONFIGNAME',		'validate' => 'string',	'type' => 'text:10:200',	'explain' => false),
				'board3_configname2_' . $module_id	=> array('lang' => 'MODULENAME_CONFIGNAME2',	'validate' => 'int',	'type' => 'text:3:3',		'explain' => true),
			),
		);
	}

	/**
	* API functions
	*/
	function install($module_id)
	{
		set_config('board3_gallery_center_rows_' . $module_id, 1);
		set_config('board3_gallery_center_columns_' . $module_id, 3);
		set_config('board3_gallery_center_crows_' . $module_id, 5);
		set_config('board3_gallery_center_contests_' . $module_id, 0);
		set_config('board3_gallery_small_mode_' . $module_id, 1);
		set_config('board3_gallery_small_rows_' . $module_id, 1);
		set_config('board3_gallery_small_display_' . $module_id, 127);
		set_config('board3_gallery_small_pgalleries_' . $module_id, 0);
		set_config('board3_gallery_center_mode_' . $module_id, 0);
		set_config('board3_gallery_center_display_' . $module_id, 127);
		set_config('board3_gallery_center_pgalleries_' . $module_id, 0);
		set_config('board3_gallery_center_comments_' . $module_id, 1);
		set_config('board3_gallery_index_mode_' . $module_id, 0);
		set_config('board3_gallery_index_display_' . $module_id, 127);
		set_config('board3_gallery_index_rows_' . $module_id, 1);
		set_config('board3_gallery_index_columns_' . $module_id, 4);
		set_config('board3_gallery_index_crows_' . $module_id, 5);
		set_config('board3_gallery_index_contests_' . $module_id, 0);
		set_config('board3_gallery_index_pgalleries_' . $module_id, 0);
		set_config('board3_gallery_index_comments_' . $module_id, 1);
		return true;
	}

	function uninstall($module_id)
	{
		global $db;

		$del_config = array(
			'board3_gallery_center_rows_' . $module_id,
			'board3_gallery_center_columns_' . $module_id,
			'board3_gallery_center_crows_' . $module_id,
			'board3_gallery_center_contests_' . $module_id,
			'board3_gallery_small_mode_' . $module_id,
			'board3_gallery_small_rows_' . $module_id,
			'board3_gallery_small_display_' . $module_id,
			'board3_gallery_small_pgalleries_' . $module_id,
			'board3_gallery_center_mode_' . $module_id,
			'board3_gallery_center_display_' . $module_id,
			'board3_gallery_center_pgalleries_' . $module_id,
			'board3_gallery_center_comments_' . $module_id,
			'board3_gallery_index_mode_' . $module_id,
			'board3_gallery_index_display_' . $module_id,
			'board3_gallery_index_rows_' . $module_id,
			'board3_gallery_index_columns_' . $module_id,
			'board3_gallery_index_crows_' . $module_id,
			'board3_gallery_index_contests_' . $module_id,
			'board3_gallery_index_pgalleries_' . $module_id,
			'board3_gallery_index_comments_' . $module_id,
		);
		$sql = 'DELETE FROM ' . CONFIG_TABLE . '
			WHERE ' . $db->sql_in_set('config_name', $del_config);
		return $db->sql_query($sql);
	}
	
	/**
	* Display recent images & comments and random images
	*
	* borrowed from phpBB Gallery (SVN 1150)
	* @author: nickvergessen
	* @function: recent_gallery_images
	*/
	function small_gallery_images($ints, $display, $mode, $include_pgalleries = true, $mode_id = '', $id = 0)
	{
		global $auth, $cache, $config, $db, $gallery_config, $portal_config, $template, $user;
		global $gallery_root_path, $phpbb_root_path, $phpEx;

		$gallery_root_path = (!$gallery_root_path) ? GALLERY_ROOT_PATH : $gallery_root_path;
		$user->add_lang(array('mods/gallery', 'mods/gallery_mcp', 'mods/info_acp_b3p_gallery'));

		if (!function_exists('generate_text_for_display'))
		{
			include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
		}
		if (!function_exists('load_gallery_config'))
		{
			$recent_image_addon = true;
			include($phpbb_root_path . $gallery_root_path . 'includes/common.' . $phpEx);
			include($phpbb_root_path . $gallery_root_path . 'includes/permissions.' . $phpEx);
		}
		if (!function_exists('assign_image_block'))
		{
			include($phpbb_root_path . $gallery_root_path . 'includes/functions_display.' . $phpEx);
		}
		$portal_config = obtain_portal_config();
		if (!function_exists('obtain_portal_config'))
		{
			include($phpbb_root_path . 'portal/includes/functions.' . $phpEx);
		}
		$album_id = $user_id = 0;
		switch ($mode_id)
		{
			case 'album':
				$album_id = $id;
			break;
			case 'user':
				$user_id = $id;
			break;
		}

		$limit_sql = $ints['small_rows'] * $ints['small_columns'];

		if ($album_id && !(gallery_acl_check('i_view', $album_id) || gallery_acl_check('m_status', $album_id)))
		{
			return;
		}

		$moderate_albums = $view_albums = array();
		if ($album_id)
		{
			if (gallery_acl_check('i_view', $album_id))
			{
				$view_albums[] = $album_id;
				$sql_permission_where = '(image_album_id = ' . $album_id . ' AND image_status <> ' . IMAGE_UNAPPROVED . ')';
			}
			if (gallery_acl_check('m_status', $album_id))
			{
				$moderate_albums[] = $album_id;
				$sql_permission_where = '(image_album_id = ' . $album_id . ')';
			}
		}
		else
		{
			$moderate_albums = gallery_acl_album_ids('m_status', 'array', true, $include_pgalleries);
			$view_albums = array_diff(gallery_acl_album_ids('i_view', 'array', true, $include_pgalleries), $moderate_albums);
			//$comment_albums = gallery_acl_album_ids('c_read', 'array', true, $include_pgalleries);

			$sql_permission_where = '(';
			$sql_permission_where .= ((sizeof($view_albums)) ? '(' . $db->sql_in_set('image_album_id', $view_albums) . ' AND image_status <> ' . IMAGE_UNAPPROVED . ')' : '');
			$sql_permission_where .= ((sizeof($moderate_albums)) ? ((sizeof($view_albums)) ? ' OR ' : '') . '(' . $db->sql_in_set('image_album_id', $moderate_albums, false, true) . ')' : '');
			$sql_permission_where .= ($user_id) ? ') AND image_user_id = ' . $user_id : ')';
		}

		if (sizeof($view_albums) || sizeof($moderate_albums))
		{
			$images = $recent_images = $random_images = $contest_images = array();
			// First step: grab all the IDs we are going to display ...
			if ($mode & RRC_MODE_RECENT)
			{
				$sql = 'SELECT image_id
					FROM ' . GALLERY_IMAGES_TABLE . "
					WHERE $sql_permission_where
					ORDER BY image_time DESC";
				$result = $db->sql_query_limit($sql, $limit_sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$images[] = $row['image_id'];
					$recent_images[] = $row['image_id'];
				}
				$db->sql_freeresult($result);
			}
			if ($mode & RRC_MODE_RANDOM)
			{
				switch ($db->sql_layer)
				{
					case 'postgres':
						$random_sql = 'RANDOM()';
					break;
					case 'mssql':
					case 'mssql_odbc':
						$random_sql = 'NEWID()';
					break;
					default:
						$random_sql = 'RAND()';
					break;
				}

				$sql = 'SELECT image_id
					FROM ' . GALLERY_IMAGES_TABLE . "
					WHERE $sql_permission_where
					ORDER BY " . $random_sql;
				$result = $db->sql_query_limit($sql, $limit_sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$images[] = $row['image_id'];
					$random_images[] = $row['image_id'];
				}
				$db->sql_freeresult($result);
			}

			// Second step: grab the data ...
			$images = array_unique($images);
			if (sizeof($images))
			{
				$sql = 'SELECT i.*, a.album_name, a.album_status, a.album_id, a.album_user_id
					FROM ' . GALLERY_IMAGES_TABLE . ' i
					LEFT JOIN ' . GALLERY_ALBUMS_TABLE . ' a
						ON i.image_album_id = a.album_id
					WHERE ' . $db->sql_in_set('i.image_id', $images, false, true) . '
					ORDER BY i.image_time DESC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$images_data[$row['image_id']] = $row;
				}
				$db->sql_freeresult($result);
			}

			// Third step: put the images
			if (sizeof($recent_images))
			{
				$num = 0;
				$template->assign_block_vars('small_imageblock', array(
					'U_SMALL_BLOCK'			=> append_sid("{$phpbb_root_path}{$gallery_root_path}search.$phpEx", 'search_id=recent'),
					'SMALL_BLOCK_NAME'		=> ($config['board3_gallery_small_rows_' . $module_id] == 1) ? $user->lang['RECENT_IMAGE'] : $user->lang['RECENT_IMAGES'],
				));
				foreach ($recent_images as $recent_image)
				{
					if (($num % $ints['small_columns']) == 0)
					{
						$template->assign_block_vars('small_imageblock.small_imagerow', array());
					}
					assign_image_block('small_imageblock.small_imagerow.small_image', $images_data[$recent_image], $images_data[$recent_image]['album_status'], $display);
					$num++;
				}
				while (($num % $ints['small_columns']) > 0)
				{
					$template->assign_block_vars('small_imageblock.small_imagerow.no_small_image', array());
					$num++;
				}
			}
			if (sizeof($random_images))
			{
				$num = 0;
				$template->assign_block_vars('small_imageblock', array(
					'U_SMALL_BLOCK'			=> append_sid("{$phpbb_root_path}{$gallery_root_path}search.$phpEx", 'search_id=random'),
					'SMALL_BLOCK_NAME'		=> ($config['board3_gallery_small_rows_' . $module_id] == 1) ? $user->lang['RANDOM_IMAGE'] : $user->lang['RANDOM_IMAGES'],
				));
				foreach ($random_images as $random_image)
				{
					if (($num % $ints['small_columns']) == 0)
					{
						$template->assign_block_vars('small_imageblock.small_imagerow', array());
					}
					assign_image_block('small_imageblock.small_imagerow.small_image', $images_data[$random_image], $images_data[$random_image]['album_status'], $display);
					$num++;
				}
				while (($num % $ints['small_columns']) > 0)
				{
					$template->assign_block_vars('small_imageblock.small_imagerow.no_small_image', array());
					$num++;
				}
			}
		}

		$template->assign_vars(array(
			'S_SMALL_THUMBNAIL_SIZE'		=> $gallery_config['thumbnail_size'] + 20 + (($gallery_config['thumbnail_info_line']) ? 16 : 0),
			'S_SMALL_COL_WIDTH'				=> (100 / $ints['small_columns']) . '%',
			'S_SMALL_COLS'					=> $ints['small_columns'],
			'S_SMALL_RANDOM'				=> ($mode & RRC_MODE_RANDOM) ? true : false,
			'S_SMALL_RECENT'				=> ($mode & RRC_MODE_RECENT) ? true : false,
		));
	}

}

?>