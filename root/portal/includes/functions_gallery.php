<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: functions_gallery.php 54 2009-05-18 12:50:40Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
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
				'SMALL_BLOCK_NAME'		=> ($portal_config['portal_pg_small_rows'] == 1) ? $user->lang['RECENT_IMAGE'] : $user->lang['RECENT_IMAGES'],
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
				'SMALL_BLOCK_NAME'		=> ($portal_config['portal_pg_small_rows'] == 1) ? $user->lang['RANDOM_IMAGE'] : $user->lang['RANDOM_IMAGES'],
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

?>