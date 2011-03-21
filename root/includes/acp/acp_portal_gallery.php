<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: acp_portal_gallery.php 57 2009-05-28 15:27:31Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
* borrowed from phpBB3
* @author: phpBB Group
* @file: acp_boards
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_portal_gallery
{
	var $u_action;
	var $new_config = array();

	function main($id, $mode)
	{
		global $db, $user, $auth, $cache, $template, $gallery_config, $portal_config;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$gallery_root_path = GALLERY_ROOT_PATH;
		
		if(!defined('ALBUM_CAT'))
		{
			include($phpbb_root_path . $gallery_root_path . 'includes/constants.' . $phpEx);
		}
		if(!function_exists('load_gallery_config'))
		{
			include($phpbb_root_path . $gallery_root_path . 'includes/functions.' . $phpEx);
		}
		if(!function_exists('obtain_portal_config'))
		{
			include($phpbb_root_path . 'portal/includes/functions.' . $phpEx);
		}

		$gallery_config = load_gallery_config();
		$portal_config = obtain_portal_config();

		$user->add_lang('mods/info_acp_b3p_gallery');
		$user->add_lang('mods/gallery_acp');
		$user->add_lang('mods/gallery');

		$submit = (isset($_POST['submit'])) ? true : false;

		$form_key = 'acp_time';
		add_form_key($form_key);

		switch ($mode)
		{
			case 'gallery':
				$display_vars = array(
					'title'	=> 'ACP_PORTAL_GALLERY_SETTINGS',
					'vars'	=> array(
						'legend1'						=> 'ACP_PORTAL_GALLERY_SETTINGS_RIGHT',
						'portal_pg_small_mode'			=> array('lang' => 'RRC_GINDEX_MODE',		'validate' => 'int',		'type' => 'custom',			'explain' => true,	'method' => 'rrc_modes'),
						'portal_pg_small_rows'			=> array('lang' => 'RRC_GINDEX_ROWS',		'validate' => 'int',		'type' => 'text:3:3',		'explain' => false),
						'portal_pg_small_display'		=> array('lang' => 'RRC_DISPLAY_OPTIONS',	'validate' => '',		'type' => 'custom',			'explain' => false,	'method' => 'rrc_display'),
						'portal_pg_small_pgalleries'	=> array('lang' => 'RRC_GINDEX_PGALLERIES',	'validate' => 'bool',	'type' => 'radio:yes_no',	'explain' => false),

						'legend2'						=> 'ACP_PORTAL_GALLERY_SETTINGS_CENTER',
						'portal_pg_center_mode'			=> array('lang' => 'RRC_GINDEX_MODE',		'validate' => 'int',	'type' => 'custom',			'explain' => true,	'method' => 'rrc_modes'),
						'portal_pg_center_rows'			=> array('lang' => 'RRC_GINDEX_ROWS',		'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_center_columns'		=> array('lang' => 'RRC_GINDEX_COLUMNS',	'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_center_comments'		=> array('lang' => 'RRC_GINDEX_COMMENTS',	'validate' => 'bool',	'type' => 'radio:yes_no',	'explain' => false),
						'portal_pg_center_crows'		=> array('lang' => 'RRC_GINDEX_CROWS',		'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_center_contests'		=> array('lang' => 'RRC_GINDEX_CONTESTS',	'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_center_display'		=> array('lang' => 'RRC_DISPLAY_OPTIONS',	'validate' => '',		'type' => 'custom',			'explain' => false,	'method' => 'rrc_display'),
						'portal_pg_center_pgalleries'	=> array('lang' => 'RRC_GINDEX_PGALLERIES',	'validate' => 'bool',	'type' => 'radio:yes_no',	'explain' => false),

						'legend3'						=> 'ACP_PORTAL_GALLERY_SETTINGS_INDEX',
						'portal_pg_index_mode'			=> array('lang' => 'RRC_GINDEX_MODE',		'validate' => 'int',	'type' => 'custom',			'explain' => true,	'method' => 'rrc_modes'),
						'portal_pg_index_rows'			=> array('lang' => 'RRC_GINDEX_ROWS',		'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_index_columns'		=> array('lang' => 'RRC_GINDEX_COLUMNS',	'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_index_comments'		=> array('lang' => 'RRC_GINDEX_COMMENTS',	'validate' => 'bool',	'type' => 'radio:yes_no',	'explain' => false),
						'portal_pg_index_crows'			=> array('lang' => 'RRC_GINDEX_CROWS',		'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_index_contests'		=> array('lang' => 'RRC_GINDEX_CONTESTS',	'validate' => 'int',	'type' => 'text:7:3',		'explain' => false),
						'portal_pg_index_display'		=> array('lang' => 'RRC_DISPLAY_OPTIONS',	'validate' => '',		'type' => 'custom',			'explain' => false,	'method' => 'rrc_display'),
						'portal_pg_index_pgalleries'	=> array('lang' => 'RRC_GINDEX_PGALLERIES',	'validate' => 'bool',	'type' => 'radio:yes_no',	'explain' => false),
					)
				);
			break;
			default:
				trigger_error('NO_MODE', E_USER_ERROR);
			break;
		}

		$this->new_config = $config;
		$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
		$error = array();

		// We validate the complete config if whished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);
		if ($submit && !check_form_key($form_key))
		{
			$error[] = $user->lang['FORM_INVALID'];
		}

		// Do not write values if there is an error
		if (sizeof($error))
		{
			$submit = false;
		}

		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				// Check for RRC-display-options
				if (isset($null['method']) && (($null['method'] == 'rrc_display') || ($null['method'] == 'rrc_modes')))
				{
					// Changing the value, casted by int to not mess up anything
					$config_value = (int) array_sum(request_var($config_name, array(0)));
				}
				set_portal_config($config_name, $config_value);
			}
		}

		if ($submit)
		{
			$cache->destroy('sql', PORTAL_CONFIG_TABLE);
			add_log('admin', 'LOG_PORTAL_CONFIG', $user->lang['ACP_PORTAL_' . strtoupper($mode) . '_INFO']);
			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		$this->tpl_name = 'acp_board';
		$this->page_title = $display_vars['title'];

		$title_explain = $user->lang[$display_vars['title'] . '_EXPLAIN'];

		$title_explain .= ( $display_vars['title'] == 'ACP_PORTAL_GALLERY_SETTINGS' ) ? '<br /><br />' . sprintf($user->lang['ACP_PORTAL_GALLERY_VERSION'], $portal_config['portal_gallery_version']) : '';

	
		$template->assign_vars(array(
			'L_TITLE'			=> $user->lang[$display_vars['title']],
			'L_TITLE_EXPLAIN'	=> $title_explain,

			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),

			'U_ACTION'			=> $this->u_action)
		);

		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'		=> true,
					'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
				);

				continue;
			}

			$this->new_config[$config_key] = $portal_config[$config_key];
			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'])
			{
				$l_explain = (isset($user->lang[$vars['lang'] . '_EXP'])) ? $user->lang[$vars['lang'] . '_EXP'] : '';
			}

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', array(
				'KEY'			=> $config_key,
				'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> $content,
			));

			unset($display_vars['vars'][$config_key]);
		}
	}
	
	/**
	* Select Config on Board3 Portal
	*
	* borrowed from phpBB Gallery (SVN 1150)
	* @author: nickvergessen
	* @function: rrc_modes
	*/
	function rrc_modes($value, $key)
	{
		global $user;

		$rrc_mode_options = '';

		$rrc_mode_options .= "<option value='" . RRC_MODE_NONE . "'>" . $user->lang['RRC_MODE_NONE'] . '</option>';
		$rrc_mode_options .= '<option' . (($value & RRC_MODE_RECENT) ? ' selected="selected"' : '') . " value='" . RRC_MODE_RECENT . "'>" . $user->lang['RRC_MODE_RECENT'] . '</option>';
		$rrc_mode_options .= '<option' . (($value & RRC_MODE_RANDOM) ? ' selected="selected"' : '') . " value='" . RRC_MODE_RANDOM . "'>" . $user->lang['RRC_MODE_RANDOM'] . '</option>';
		if ($key != 'portal_pg_small_mode')
		{
			$rrc_mode_options .= '<option' . (($value & RRC_MODE_COMMENT) ? ' selected="selected"' : '') . " value='" . RRC_MODE_COMMENT . "'>" . $user->lang['RRC_MODE_COMMENTS'] . '</option>';
		}

		// Cheating is an evil-thing, but most times it's successful, that's why it is used.
		return '<input type="hidden" name="config[' . $key . ']" value="' . $value . '" /><select name="' . $key . '[]" multiple="multiple" id="' . $key . '">' . $rrc_mode_options . '</select>';
	}

	/**
	* Select RRC display options
	*
	* borrowed from phpBB Gallery (SVN 1150)
	* @author: nickvergessen
	* @function: rrc_display
	*/
	function rrc_display($value, $key)
	{
		global $user;

		$rrc_display_options = '';

		$rrc_display_options .= '<option value="' . RRC_DISPLAY_NONE . '">' . $user->lang['RRC_DISPLAY_NONE'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_ALBUMNAME) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_ALBUMNAME . "'>" . $user->lang['RRC_DISPLAY_ALBUMNAME'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_COMMENTS) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_COMMENTS . "'>" . $user->lang['RRC_DISPLAY_COMMENTS'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_IMAGENAME) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_IMAGENAME . "'>" . $user->lang['RRC_DISPLAY_IMAGENAME'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_IMAGETIME) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_IMAGETIME . "'>" . $user->lang['RRC_DISPLAY_IMAGETIME'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_IMAGEVIEWS) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_IMAGEVIEWS . "'>" . $user->lang['RRC_DISPLAY_IMAGEVIEWS'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_USERNAME) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_USERNAME . "'>" . $user->lang['RRC_DISPLAY_USERNAME'] . '</option>';
		$rrc_display_options .= '<option' . (($value & RRC_DISPLAY_RATINGS) ? ' selected="selected"' : '') . " value='" . RRC_DISPLAY_RATINGS . "'>" . $user->lang['RRC_DISPLAY_RATINGS'] . '</option>';

		// Cheating is an evil-thing, but most times it's successful, that's why it is used.
		return '<input type="hidden" name="config[' . $key . ']" value="' . $value . '" /><select name="' . $key . '[]" multiple="multiple" id="' . $key . '">' . $rrc_display_options . '</select>';
	}
}

?>